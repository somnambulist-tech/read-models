<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels;

use BadMethodCallException;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use IlluminateAgnostic\Str\Support\Str;
use Somnambulist\Collection\Collection;
use Somnambulist\Collection\Interfaces\ExportableInterface;
use Somnambulist\ReadModels\Exceptions\EntityNotFoundException;
use Somnambulist\ReadModels\Relationships\AbstractRelationship;

/**
 * Class Builder
 *
 * @package    Somnambulist\ReadModels
 * @subpackage Somnambulist\ReadModels\Builder
 */
class Builder
{

    /**
     * @var Model
     */
    private $model;

    /**
     * @var QueryBuilder
     */
    private $query;

    /**
     * The relationships that should be eager loaded.
     *
     * @var array
     */
    protected $eagerLoad = [];

    /**
     * Constructor.
     *
     * @param Model        $model
     * @param QueryBuilder $query
     */
    public function __construct(Model $model, QueryBuilder $query)
    {
        $this->model = $model;
        $this->query = $query->select('*')->from($model->getTable(), $model->getTableAlias());
    }

    /**
     * Set the relationships that should be eager loaded.
     *
     * @param mixed $relations
     *
     * @return $this
     */
    public function with(...$relations)
    {
        $eagerLoad = $this->parseWithRelations($relations);

        $this->eagerLoad = array_merge($this->eagerLoad, $eagerLoad);

        return $this;
    }

    /**
     * Parse a list of relations into individuals.
     *
     * @param array $relations
     *
     * @return array
     */
    protected function parseWithRelations(array $relations)
    {
        $results = [];

        foreach ($relations as $name => $constraints) {
            // If the "name" value is a numeric key, we can assume that no
            // constraints have been specified. We'll just put an empty
            // Closure there, so that we can treat them all the same.
            if (is_numeric($name)) {
                $name = $constraints;

                [$name, $constraints] = Str::contains($name, ':')
                    ? $this->createSelectWithConstraint($name)
                    : [
                        $name, function () {
                            //
                        },
                    ];
            }

            // We need to separate out any nested includes, which allows the developers
            // to load deep relationships using "dots" without stating each level of
            // the relationship with its own key in the array of eager-load names.
            $results = $this->addNestedWiths($name, $results);

            $results[$name] = $constraints;
        }

        return $results;
    }

    /**
     * Create a constraint to select the given columns for the relation.
     *
     * @param string $name
     *
     * @return array
     */
    protected function createSelectWithConstraint($name)
    {
        return [
            explode(':', $name)[0], function ($query) use ($name) {
                $query->select(explode(',', explode(':', $name)[1]));
            },
        ];
    }

    /**
     * Parse the nested relationships in a relation.
     *
     * @param string $name
     * @param array  $results
     *
     * @return array
     */
    protected function addNestedWiths($name, $results)
    {
        $progress = [];

        // If the relation has already been set on the result array, we will not set it
        // again, since that would override any constraints that were already placed
        // on the relationships. We will only set the ones that are not specified.
        foreach (explode('.', $name) as $segment) {
            $progress[] = $segment;

            if (!isset($results[$last = implode('.', $progress)])) {
                $results[$last] = function () {
                    //
                };
            }
        }

        return $results;
    }

    /**
     * Find the model by primary key, optionally returning the columns
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
     * @param string $id
     * @param string ...$columns
     *
     * @return Model
     * @throws EntityNotFoundException
     */
    public function findOrFail($id, ...$columns): Model
    {
        if (null === $model = $this->find($id, ...$columns)) {
            throw EntityNotFoundException::noMatchingRecordFor(get_class($this->model), $this->model->getTable(), $id);
        }

        return $model;
    }

    /**
     * @return Collection
     */
    public function fetch(): Collection
    {
        $models = new Collection();

        if ($stmt = $this->getQuery()->execute()) {
            $stmt->setFetchMode(FetchMode::ASSOCIATIVE);

            foreach ($stmt as $row) {
                $models->add($this->model->newModel($row));
            }

            if ($models->count() > 0) {
                $this->eagerLoadRelationships($models);
            }
        }

        return $models;
    }

    public function eagerLoadRelationships(Collection $models)
    {
        $ids = $models->extract($this->model->getPrimaryKey())->toArray();

        foreach ($this->eagerLoad as $name => $constraints) {
            /** @var AbstractRelationship $load */
            //$load = $this->model->newModel()->{$name}();

            //dump($load);
            $models->each(function ($model) use ($name) {
                $model->{$name};
            });
        }
    }

    /**
     * @param string $column
     *
     * @return string
     */
    public function prefixTableAlias(string $column): string
    {
        if (false !== stripos($column, '.')) {
            return $column;
        }

        return sprintf('%s.%s', ($this->model->getTableAlias() ?: $this->model->getTable()), $column);
    }

    /**
     * @param string ...$columns
     *
     * @return Builder
     */
    public function select(...$columns): Builder
    {
        if (empty($columns)) {
            $columns = ['*'];
        }

        $columns = array_map(function ($column) {
            return $this->prefixTableAlias($column);
        }, $columns);

        $this->query->addSelect($columns);

        return $this;
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
        return $this->whereColumn($this->model->getAliasedPrimaryKey(), '=', $id);
    }

    /**
     * @return ExpressionBuilder
     */
    public function expression(): ExpressionBuilder
    {
        return $this->query->expr();
    }

    /**
     * @param string $andOr
     *
     * @return string
     */
    private function whereMethod(string $andOr): string
    {
        return (in_array($andOr, ['and', 'or']) ? $andOr : 'and') . 'Where';
    }

    /**
     * Generate a placeholder key to bind to the query
     *
     * @param string $column
     *
     * @return string
     */
    private function createPlaceholderKey(string $column): string
    {
        // ensure that any bound parameter will always have a unique number
        static $index = 0;

        return sprintf(':bind_%s_%s', Str::slug(Str::replaceArray('.', ['_'], $this->prefixTableAlias($column)), '_'), ++$index);
    }

    /**
     * @param string $column
     * @param        $values
     * @param string $andOr
     * @param bool   $not
     *
     * @return $this
     */
    public function whereIn(string $column, $values, string $andOr = 'and', bool $not = false): self
    {
        $method = $this->whereMethod($andOr);
        $expr   = $not ? 'notIn' : 'in';

        if ($values instanceof ExportableInterface) {
            $values = $values->toArray();
        }

        $placeholders = Collection::collect($values)
            ->transform(function ($value) use ($column) {
                $this->query->setParameter($key = $this->createPlaceholderKey($column), $value);

                return $key;
            })
            ->toArray()
        ;

        $this->query->{$method}($this->expression()->{$expr}($this->prefixTableAlias($column), $placeholders));

        return $this;
    }

    public function whereNotIn(string $column, $values, string $andOr = 'and'): self
    {
        return $this->whereIn($column, $values, $andOr, true);
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
     * @param string $column
     * @param string $operator Equality operator e.g. <, >, =, !=, <>, LIKE, ILIKE etc
     * @param mixed  $value
     * @param string $andOr    Should the where be AND (expression) or OR (expression)
     *
     * @return Builder
     */
    public function whereColumn(string $column, string $operator, $value, string $andOr = 'and'): self
    {
        $key    = $this->createPlaceholderKey($column);
        $method = $this->whereMethod($andOr);

        $this->query
            ->{$method}($this->query->expr()->comparison($this->prefixTableAlias($column), $operator, $key))
            ->setParameter($key, $value)
        ;

        return $this;
    }

    /**
     * Add an or to the where
     *
     * @param string $column
     * @param string $operator
     * @param mixed  $value
     *
     * @return Builder
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
     * @return Builder
     */
    public function whereNull(string $column, string $andOr = 'and', bool $not = false): self
    {
        $method = $this->whereMethod($andOr);
        $expr   = $not ? 'isNotNull' : 'isNull';

        $this->query->{$method}($this->query->expr()->{$expr}($this->prefixTableAlias($column)));

        return $this;
    }

    /**
     * @param string $column
     *
     * @return Builder
     */
    public function whereNotNull(string $column): self
    {
        return $this->whereNull($column, 'and', true);
    }

    /**
     * @param string $column
     *
     * @return Builder
     */
    public function orWhereNull(string $column): self
    {
        return $this->whereNull($column, 'or');
    }

    /**
     * @param string $column
     *
     * @return Builder
     */
    public function orWhereNotNull(string $column): self
    {
        return $this->whereNull($column, 'or', true);
    }

    /**
     * @param string $column
     * @param mixed  $start
     * @param mixed  $end
     * @param string $andOr
     * @param bool   $not
     *
     * @return Builder
     */
    public function whereBetween(string $column, $start, $end, string $andOr = 'and', bool $not = false): self
    {
        $method = $this->whereMethod($andOr);
        $expr   = ($not ? 'NOT' : '') . ' BETWEEN';
        $key1   = $this->createPlaceholderKey($column);
        $key2   = $this->createPlaceholderKey($column);

        $this->query->{$method}(sprintf('%s %s %s AND %s', $this->prefixTableAlias($column), $expr, $key1, $key2));
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
     * @param string $column
     *
     * @return Builder
     */
    public function groupBy(string $column): self
    {
        $this->query->addGroupBy($this->prefixTableAlias($column));

        return $this;
    }

    /**
     * @param string $column
     * @param string $dir
     *
     * @return Builder
     */
    public function orderBy(string $column, string $dir = 'ASC'): self
    {
        $this->query->addOrderBy($this->prefixTableAlias($column), $dir);

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return Builder
     */
    public function limit(int $limit): self
    {
        $this->query->setMaxResults($limit);

        return $this;
    }

    /**
     * @param int $offset
     *
     * @return Builder
     */
    public function offset(int $offset): self
    {
        $this->query->setFirstResult($offset);

        return $this;
    }

    /**
     * Gets the underlying DBAL query builder
     *
     * @return QueryBuilder
     */
    public function getQuery(): QueryBuilder
    {
        return $this->query;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Allow pass through to QueryBuilder but return this Builder
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return Builder
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
                sprintf('Method "%s" is not supported for pass through (%s)', $name, implode(', ', $allowed))
            );
        }
        if (!method_exists($this->query, $name)) {
            throw new BadMethodCallException(
                sprintf('Unable to route "%s" to %s or %s', $name, get_class($this), get_class($this->query))
            );
        }

        $this->query->{$name}(...$arguments);

        return $this;
    }
}
