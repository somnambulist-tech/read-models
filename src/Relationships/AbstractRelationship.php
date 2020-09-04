<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use Doctrine\Common\Collections\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use Pagerfanta\Pagerfanta;
use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\ReadModels\Contracts\Queryable;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\ModelBuilder;
use Somnambulist\ReadModels\Utils\ProxyTo;

/**
 * Class AbstractRelationship
 *
 * @package    Somnambulist\ReadModels\Relationships
 * @subpackage Somnambulist\ReadModels\Relationships\AbstractRelationship
 *
 * Supports proxying calls to the Builder class
 *
 * @method ExpressionBuilder expression()
 * @method Model find()
 * @method Model findOrFail()
 * @method Model getModel()
 * @method mixed getParameter(string $key)
 * @method array getParameters()
 * @method mixed getParameterType(string $key)
 * @method array getParameterTypes()
 * @method bool hasSelectExpression(string $expression)
 * @method QueryBuilder getQueryBuilder()
 * @method int count()
 * @method Pagerfanta paginate(int $page = 1, int $perPage = 30)
 * @method AbstractRelationship andHaving(string $expression)
 * @method AbstractRelationship groupBy(string $column)
 * @method AbstractRelationship having(string $expression)
 * @method AbstractRelationship innerJoin(string $fromAlias, string $join, string $alias, string $condition)
 * @method AbstractRelationship join(string $fromAlias, string $join, string $alias, string $condition)
 * @method AbstractRelationship leftJoin(string $fromAlias, string $join, string $alias, string $condition)
 * @method AbstractRelationship limit(int $limit)
 * @method AbstractRelationship newQuery()
 * @method AbstractRelationship offset(int $offset)
 * @method AbstractRelationship orderBy(string $column, string $dir)
 * @method AbstractRelationship orHaving(string $expression)
 * @method AbstractRelationship orWhere(string $expression, array $values)
 * @method AbstractRelationship orWhereBetween(string $column, $start, $end)
 * @method AbstractRelationship orWhereColumn(string $column, string $operator, $value)
 * @method AbstractRelationship orWhereIn(string $column, $values)
 * @method AbstractRelationship orWhereNotBetween(string $column, $start, $end)
 * @method AbstractRelationship orWhereNotIn(string $column, $values)
 * @method AbstractRelationship orWhereNotNull(string $column)
 * @method AbstractRelationship orWhereNull(string $column)
 * @method AbstractRelationship rightJoin(string $fromAlias, string $join, string $alias, string $condition)
 * @method AbstractRelationship select(...$columns)
 * @method AbstractRelationship setParameter(string $key, $value, ?string|int $type)
 * @method AbstractRelationship setParameters(array $parameters)
 * @method AbstractRelationship where(string $expression, array $values)
 * @method AbstractRelationship whereBetween(string $column, $start, $end)
 * @method AbstractRelationship whereColumn(string $column, string $operator, $value)
 * @method AbstractRelationship whereIn(string $column, $values)
 * @method AbstractRelationship whereNotBetween(string $column, $start, $end)
 * @method AbstractRelationship whereNotIn(string $column, $values)
 * @method AbstractRelationship whereNotNull(string $column)
 * @method AbstractRelationship whereNull(string $column)
 * @method AbstractRelationship wherePrimaryKey(int|string $id)
 * @method AbstractRelationship with(...$relationships)
 */
abstract class AbstractRelationship implements Queryable
{

    protected ModelBuilder $query;
    protected Model $parent;
    protected Model $related;

    /**
     * True if the relationship has many results
     */
    protected bool $hasMany = false;

    public function __construct(ModelBuilder $builder, Model $parent)
    {
        $this->parent  = $parent;
        $this->related = $builder->model;
        $this->query   = $builder;
    }

    /**
     * Add the constraints required for loading a set of results
     *
     * @param Collection $models
     *
     * @return AbstractRelationship
     * @internal
     */
    abstract public function addConstraints(Collection $models): self;

    /**
     * Executes and maps the loaded data to the collection of Models
     *
     * The related models will be inserted in the models relationships array at the key
     * specified by $relationship.
     *
     * @param Collection $models
     * @param string     $relationship
     *
     * @return AbstractRelationship
     * @internal
     */
    abstract public function addRelationshipResultsToModels(Collection $models, string $relationship): self;

    /**
     * Allows a callable to modify the current query before the results are fetched
     *
     * @param callable $constraint
     *
     * @return AbstractRelationship
     * @internal
     */
    public function addConstraintCallbackToQuery(callable $constraint): self
    {
        $constraint($this);

        return $this;
    }

    public function fetchValueForRelationship()
    {
        if ($this->hasMany()) {
            return $this->fetch();
        }

        return $this->fetch()->first();
    }

    public function fetch(): Collection
    {
        return $this->query->fetch();
    }

    public function getQuery(): ModelBuilder
    {
        return $this->query;
    }

    public function getParent(): Model
    {
        return $this->parent;
    }

    public function getRelated(): Model
    {
        return $this->related;
    }

    public function hasMany(): bool
    {
        return $this->hasMany;
    }

    public function __call($name, $arguments)
    {
        $result = (new ProxyTo())($this->query, $name, $arguments);

        if ($result === $this->query) {
            return $this;
        }

        return $result;
    }
}
