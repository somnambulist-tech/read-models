<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels;

use BadMethodCallException;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use function get_class;
use IlluminateAgnostic\Str\Support\Str;
use InvalidArgumentException;
use Pagerfanta\Pagerfanta;
use RuntimeException;
use Somnambulist\Collection\Contracts\Arrayable;
use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\ReadModels\Contracts\Queryable;
use Somnambulist\ReadModels\Exceptions\EntityNotFoundException;
use Somnambulist\ReadModels\Exceptions\NoResultsException;
use Somnambulist\ReadModels\Relationships\AbstractRelationship;
use Somnambulist\ReadModels\Utils\ProxyTo;
use function sprintf;

/**
 * Class ModelBuilder
 *
 * @package    Somnambulist\ReadModels
 * @subpackage Somnambulist\ReadModels\ModelBuilder
 *
 * These methods pass through to the underlying QueryBuilder instance.
 *
 * @property-read ModelMetadata $meta
 * @property-read Model         $model
 * @property-read QueryBuilder  $query
 *
 * @method ModelBuilder join(string $fromAlias, string $join, string $alias, $conditions)
 * @method ModelBuilder innerJoin(string $fromAlias, string $join, string $alias, $conditions)
 * @method ModelBuilder leftJoin(string $fromAlias, string $join, string $alias, $conditions)
 * @method ModelBuilder rightJoin(string $fromAlias, string $join, string $alias, $conditions)
 * @method ModelBuilder setParameter(string|int $key, mixed $value, $type = null)
 * @method ModelBuilder setParameters(array $parameters)
 * @method mixed getParameter(string|int $key)
 * @method array getParameters()
 * @method int getParameterType(string $key)
 * @method array getParameterTypes()
 * @method ModelBuilder having(string $expression)
 * @method ModelBuilder andHaving(string $expression)
 * @method ModelBuilder orHaving(string $expression)
 */
class ModelBuilder implements Queryable
{

    /**
     * @var Model
     */
    private $model;

    /**
     * @var ModelMetadata
     */
    private $meta;

    /**
     * @var QueryBuilder
     */
    private $query;

    /**
     * @var array
     */
    private $eagerLoad = [];

    /**
     * Constructor.
     *
     * @param Model        $model
     * @param QueryBuilder $query
     */
    public function __construct(Model $model, QueryBuilder $query)
    {
        $this->model = $model;
        $this->meta  = $model->metadata();
        $this->query = $query->from($this->meta->table(), $this->meta->tableAlias());
    }

    /**
     * Returns a new query builder instance for the set Model
     *
     * @return ModelBuilder
     */
    public function newQuery(): self
    {
        return new static($this->model, Model::connection(get_class($this->model))->createQueryBuilder());
    }

    /**
     * Find the model by primary key, optionally returning just the specified columns
     *
     * @param string $id
     * @param string ...$columns
     *
     * @return Model|null
     */
    public function find($id, ...$columns): ?Model
    {
        return $this->select(...$columns)->wherePrimaryKey($id)->limit(1)->fetch()->first() ?: null;
    }

    /**
     * Find records by the given criteria similar to EntityRepository findBy
     *
     * @param array    $criteria An array of field name -> value pairs to search
     * @param array    $orderBy An array of field name -> ASC|DESC values to order by
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return Collection
     */
    public function findBy(array $criteria, array $orderBy = [], int $limit = null, int $offset = null): Collection
    {
        foreach ($criteria as $field => $value) {
            $this->whereColumn($field, '=', $value);
        }
        foreach ($orderBy as $field => $dir) {
            $this->orderBy($field, $dir);
        }

        if ($limit) {
            $this->limit($limit);
        }
        if ($offset) {
            $this->offset($offset);
        }

        return $this->fetch();
    }

    /**
     * Returns the first record matching the criteria and order or null
     *
     * @param array $criteria An array of field name -> value pairs to search
     * @param array $orderBy  An array of field name -> ASC|DESC values to order by
     *
     * @return Model|null
     */
    public function findOneBy(array $criteria, array $orderBy = []): ?Model
    {
        return $this->findBy($criteria, $orderBy, 1)->first() ?? null;
    }

    /**
     * Find the model by the primary key, but raise an exception if not found
     *
     * @param string $id
     * @param string ...$columns
     *
     * @return Model
     * @throws EntityNotFoundException
     */
    public function findOrFail($id, ...$columns): Model
    {
        if (null === $model = $this->find($id, ...$columns)) {
            throw EntityNotFoundException::noMatchingRecordFor(get_class($this->model), $this->meta->table(), $id);
        }

        return $model;
    }

    /**
     * Executes the current query, returning a Collection of results
     *
     * @return Collection
     */
    public function fetch(): Collection
    {
        $models  = new Collection();
        $selects = (new FilterGeneratedKeysFromCollection())($this->query->getQueryPart('select'));

        if (count($selects) < 1) {
            $this->select('*');
        }

        if ($stmt = $this->query->execute()) {
            $stmt->setFetchMode(FetchMode::ASSOCIATIVE);

            foreach ($stmt as $row) {
                $models->add($this->model->new($row));
            }

            if ($models->count() > 0) {
                $this->eagerLoadRelationships($models);
            }
        }

        return $models;
    }

    /**
     * Returns the first matching result of the query, or raises an exception
     *
     * @return Model
     * @throws NoResultsException
     */
    public function fetchFirstOrFail(): Model
    {
        if (!$model = $this->fetch()->first()) {
            throw NoResultsException::noResultsForQuery(get_class($this->model), $this->query);
        }

        return $model;
    }

    /**
     * Returns the first matching result of the query, or null if no results
     *
     * @return Model|null
     */
    public function fetchFirstOrNull(): ?Model
    {
        return $this->fetch()->first() ?? null;
    }

    /**
     * Executes the current query, returning a count of total matched records
     *
     * count operates on a copy of the current query.
     *
     * @return int
     */
    public function count(): int
    {
        $query   = clone $this->query;
        $groupBy = $query->getQueryPart('groupBy');
        $selects = $query->getQueryPart('select');
        $new     = [];

        foreach ($groupBy as $item) {
            foreach ($selects as $select) {
                if (Str::contains($select, $item)) {
                    $new[] = $select;
                }
            }
        }

        $stmt = $query
            ->select($new)
            ->addSelect(sprintf('COUNT(DISTINCT %s) AS total_results', $this->meta->primaryKeyNameWithAlias()))
            ->setMaxResults(1)
            ->setFirstResult(0)
            ->execute()
        ;

        if ($stmt) {
            $stmt->setFetchMode(FetchMode::ASSOCIATIVE);

            return $stmt->fetchAll()[0]['total_results'] ?? 0;
        }

        return 0;
    }

    /**
     * Returns a paginator that can be iterated with results
     *
     * Note: the paginator may not cope with all types of select and group by. You
     * may need to scale back the types of queries you run.
     *
     * @param int $page
     * @param int $perPage
     *
     * @return Pagerfanta
     */
    public function paginate(int $page = 1, int $perPage = 30): Pagerfanta
    {
        return (new Pagerfanta(new PaginatorAdapter($this)))->setCurrentPage($page)->setMaxPerPage($perPage);
    }

    /**
     * Set the relationships that should be eager loaded
     *
     * @param mixed $relations Strings of relationship names, or an array
     *
     * @return $this
     */
    public function with(...$relations): self
    {
        $this->eagerLoad = (new GenerateRelationshipsToEagerLoad())($this->eagerLoad, ...$relations);

        return $this;
    }

    /**
     * Eager load related models to our set of model results
     *
     * @param Collection $models
     */
    private function eagerLoadRelationships(Collection $models): void
    {
        foreach ($this->eagerLoad as $name => $constraints) {
            if (false === strpos($name, '.')) {
                /** @var AbstractRelationship $load */
                $rel = $this->model->new()->getRelationship($name);
                $rel
                    ->addEagerLoadingConstraints($models)
                    ->addEagerLoadingRelationships($this->findNestedRelationshipsFor($name))
                    ->addConstraintCallbackToQuery($constraints)
                    ->addEagerLoadingResults($models, $name);
                ;
            }
        }
    }

    /**
     * Get the deeply nested relations for a given top-level relation.
     *
     * @param string $relation
     *
     * @return array
     */
    private function findNestedRelationshipsFor(string $relation): array
    {
        $nested = [];

        // We are basically looking for any relationships that are nested deeper than
        // the given top-level relationship. We will just check for any relations
        // that start with the given top relations and adds them to our arrays.
        foreach ($this->eagerLoad as $name => $constraints) {
            if (Str::contains($name, '.') && Str::startsWith($name, $relation . '.')) {
                $nested[substr($name, strlen($relation . '.'))] = $constraints;
            }
        }

        return $nested;
    }

    /**
     * @param string $column
     *
     * @return string
     */
    private function prefixColumnWithTableAlias(string $column): string
    {
        return $this->meta->prefixAlias($column);
    }

    /**
     * The DBAL Expression builder object for creating where clauses
     *
     * @return ExpressionBuilder
     */
    public function expression(): ExpressionBuilder
    {
        return $this->query->expr();
    }

    /**
     * Select specific columns from the current model
     *
     * Use multiple arguments: ->select('id', 'name', 'created_at')...
     *
     * @param string ...$columns
     *
     * @return ModelBuilder
     */
    public function select(...$columns): ModelBuilder
    {
        if (empty($columns)) {
            $columns = ['*'];
        }

        $columns = array_map(function ($column) {
            return $this->prefixColumnWithTableAlias($column);
        }, $columns);

        $this->query->select(array_unique(array_merge($this->query->getQueryPart('select'), $columns)));

        return $this;
    }

    /**
     * Returns true if the expression has been bound to the select clause
     *
     * Search is performed using "contains" and could match similar strings. For example:
     * a check for contains "user_id" would return true for any select clause that contains
     * the string user_id (user.id AS user_id, related_user_id etc).
     *
     * For better results, be sure to check for a specific expression. Selects should be
     * relatively unique, unless extremely complex.
     *
     * @param string $expression
     *
     * @return bool
     */
    public function hasSelectExpression(string $expression): bool
    {
        foreach ($this->query->getQueryPart('select') as $select) {
            if (Str::contains($select, $expression)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add a where clause on the primary key to the query
     *
     * @param mixed $id
     *
     * @return $this
     */
    public function wherePrimaryKey($id): self
    {
        return $this->whereColumn($this->meta->primaryKeyNameWithAlias(), '=', $id);
    }

    private function getAndOrWhereMethodName(string $andOr): string
    {
        return (in_array($andOr, ['and', 'or']) ? $andOr : 'and') . 'Where';
    }

    private function createParameterPlaceholderKey(string $column): string
    {
        // ensure that any bound parameter will always have a unique number
        static $index = 0;

        // placeholder name can only be ascii with underscores, hyphens and dots are not allowed
        return sprintf(
            ':bind_%s_%s',
            Str::slug(Str::replaceArray('.', ['_'], $this->prefixColumnWithTableAlias($column)), '_'),
            ++$index
        );
    }

    /**
     * Add an arbitrarily complex AND expression to the query
     *
     * This method allows raw SQL, SELECT ... and basically anything you can put in a where.
     * Values _must_ be passed as key -> value where the key is the NAMED placeholder. ?
     * placeholders are not supported in this builder.
     *
     * If the parameter is already bound, it will be overwritten with the value in the values
     * array.
     *
     * @param string $expression
     * @param array  $values
     *
     * @return ModelBuilder
     */
    public function where(string $expression, array $values = []): self
    {
        $this->query->andWhere($expression);

        foreach ($values as $key => $value) {
            if ('?' === $key) {
                throw new InvalidArgumentException(sprintf('WHERE condition must use named placeholders not ?'));
            }

            $this->query->setParameter($key, $value);
        }

        return $this;
    }

    /**
     * Add an arbitrarily complex OR expression to the query
     *
     * The same rules apply as for the AND version. Values must use named placeholders.
     *
     * @param string $expression
     * @param array  $values
     *
     * @return ModelBuilder
     */
    public function orWhere(string $expression, array $values = []): self
    {
        $this->query->orWhere($expression);

        foreach ($values as $key => $value) {
            if ('?' === $key) {
                throw new InvalidArgumentException(sprintf('WHERE condition must use named placeholders not ?'));
            }

            $this->query->setParameter($key, $value);
        }

        return $this;
    }

    /**
     * Create a WHERE column IN () clause with support for and or or and NOT IN ()
     *
     * @param string          $column
     * @param array|Arrayable $values
     * @param string          $andOr
     * @param bool            $not
     *
     * @return $this
     */
    public function whereIn(string $column, $values, string $andOr = 'and', bool $not = false): self
    {
        $method = $this->getAndOrWhereMethodName($andOr);
        $expr   = $not ? 'notIn' : 'in';

        if ($values instanceof Arrayable) {
            $values = $values->toArray();
        }

        $placeholders = Collection::collect($values)
            ->map(function ($value) use ($column) {
                $this->query->setParameter($key = $this->createParameterPlaceholderKey($column), $value);

                return $key;
            })
            ->toArray()
        ;

        $this->query->{$method}($this->expression()->{$expr}($this->prefixColumnWithTableAlias($column), $placeholders));

        return $this;
    }

    public function whereNotIn(string $column, $values): self
    {
        return $this->whereIn($column, $values, 'and', true);
    }

    public function orWhereIn(string $column, $values): self
    {
        return $this->whereIn($column, $values, 'or');
    }

    public function orWhereNotIn(string $column, $values): self
    {
        return $this->whereIn($column, $values, 'or', true);
    }

    /**
     * Add a WHERE <column> condition to the query
     *
     * Specifically works with the column. Operator can be any valid SQL operator that
     * can accept a value including like, ilike.
     *
     * @param string $column
     * @param string $operator Equality operator e.g. <, >, =, !=, <>, LIKE, ILIKE etc
     * @param mixed  $value
     * @param string $andOr    Should the where be AND (expression) or OR (expression)
     *
     * @return ModelBuilder
     */
    public function whereColumn(string $column, string $operator, $value, string $andOr = 'and'): self
    {
        $key    = $this->createParameterPlaceholderKey($column);
        $method = $this->getAndOrWhereMethodName($andOr);

        $this->query
            ->{$method}($this->expression()->comparison($this->prefixColumnWithTableAlias($column), $operator, $key))
            ->setParameter($key, $value)
        ;

        return $this;
    }

    /**
     * Add an or column to the where clause
     *
     * @param string $column
     * @param string $operator
     * @param mixed  $value
     *
     * @return ModelBuilder
     */
    public function orWhereColumn(string $column, string $operator, $value): self
    {
        return $this->whereColumn($column, $operator, $value, 'or');
    }

    /**
     * @param string $column
     * @param string $andOr Should the where be AND (expression) or OR (expression)
     * @param bool   $not
     *
     * @return ModelBuilder
     */
    public function whereNull(string $column, string $andOr = 'and', bool $not = false): self
    {
        $method = $this->getAndOrWhereMethodName($andOr);
        $expr   = $not ? 'isNotNull' : 'isNull';

        $this->query->{$method}($this->expression()->{$expr}($this->prefixColumnWithTableAlias($column)));

        return $this;
    }

    public function whereNotNull(string $column): self
    {
        return $this->whereNull($column, 'and', true);
    }

    public function orWhereNull(string $column): self
    {
        return $this->whereNull($column, 'or');
    }

    public function orWhereNotNull(string $column): self
    {
        return $this->whereNull($column, 'or', true);
    }

    /**
     * Adds a <column> BETWEEN <start> AND <end> to the query
     *
     * Start and end can be any valid value supported by the DB for BETWEEN. e.g. dates, ints, floats
     * If using a date on a datetime field, note that it is usually treated as midnight to midnight so
     * may not include all results, in those instances either go 1 day higher or set the time to
     * 23:59:59.
     *
     * @param string $column
     * @param mixed  $start
     * @param mixed  $end
     * @param string $andOr
     * @param bool   $not
     *
     * @return ModelBuilder
     */
    public function whereBetween(string $column, $start, $end, string $andOr = 'and', bool $not = false): self
    {
        $method = $this->getAndOrWhereMethodName($andOr);
        $expr   = ($not ? 'NOT' : '') . ' BETWEEN';
        $key1   = $this->createParameterPlaceholderKey($column);
        $key2   = $this->createParameterPlaceholderKey($column);

        $this->query->{$method}(sprintf('%s %s %s AND %s', $this->prefixColumnWithTableAlias($column), $expr, $key1, $key2));
        $this->query->setParameter($key1, $start);
        $this->query->setParameter($key2, $end);

        return $this;
    }

    public function whereNotBetween(string $column, $start, $end): self
    {
        return $this->whereBetween($column, $start, $end, 'and', true);
    }

    public function orWhereBetween(string $column, $start, $end): self
    {
        return $this->whereBetween($column, $start, $end, 'or');
    }

    public function orWhereNotBetween(string $column, $start, $end): self
    {
        return $this->whereBetween($column, $start, $end, 'or', true);
    }

    /**
     * Group by a column in the select clause
     *
     * Note: if you add a group by, any non-aggregate selected column must also
     * appear in the group by.
     *
     * @param string $column
     *
     * @return ModelBuilder
     */
    public function groupBy(string $column): self
    {
        $this->query->addGroupBy($this->prefixColumnWithTableAlias($column));

        return $this;
    }

    public function orderBy(string $column, string $dir = 'ASC'): self
    {
        $this->query->addOrderBy($this->prefixColumnWithTableAlias($column), $dir);

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->query->setMaxResults($limit);

        return $this;
    }

    public function offset(int $offset): self
    {
        $this->query->setFirstResult($offset);

        return $this;
    }

    /**
     * Gets the underlying DBAL query builder
     *
     * Note: this provides total access to all bound data include query parts.
     * Use with caution.
     *
     * @return QueryBuilder
     * @internal
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->query;
    }

    /**
     * @return Model
     * @internal
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Allow pass through to certain QueryBuilder methods but return this Builder
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return ModelBuilder
     * @throws BadMethodCallException
     */
    public function __call($name, $arguments)
    {
        $allowed = [
            'setParameter',
            'getParameters', 'getParameter',
            'getParameterTypes', 'getParameterType',
            'join', 'innerJoin', 'leftJoin', 'rightJoin',
            'having', 'andHaving', 'orHaving',
        ];

        if (!in_array($name, $allowed)) {
            throw new BadMethodCallException(
                sprintf(
                    'Method "%s" is not supported for pass through on "%s"; expected one of (%s)',
                    $name, static::class, implode(', ', $allowed)
                )
            );
        }

        (new ProxyTo())($this->query, $name, $arguments);

        return $this;
    }

    public function __get($name)
    {
        if (in_array($name, ['meta', 'model', 'query'])) {
            return $this->{$name};
        }

        throw new RuntimeException(sprintf('Unknown property "%s" requested on "%s"', $name, static::class));
    }

    public function __clone()
    {
        $this->query = clone $this->query;
    }
}
