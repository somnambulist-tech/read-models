<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Contracts;

use Doctrine\Common\Collections\ExpressionBuilder;

/**
 * Tags an object as supporting or allowing pass through into the Builder
 * object. This allows for type completion and hints where they might not
 * otherwise exist.
 *
 * @method ExpressionBuilder expression()
 * @method Queryable andHaving(string $expression)
 * @method Queryable groupBy(string $column)
 * @method Queryable having(string $expression)
 * @method Queryable innerJoin(string $fromAlias, string $join, string $alias, string $condition)
 * @method Queryable join(string $fromAlias, string $join, string $alias, string $condition)
 * @method Queryable leftJoin(string $fromAlias, string $join, string $alias, string $condition)
 * @method Queryable limit(int $limit)
 * @method Queryable offset(int $offset)
 * @method Queryable orderBy(string $column, string $dir)
 * @method Queryable orHaving(string $expression)
 * @method Queryable orWhere(string $expression, array $values)
 * @method Queryable orWhereBetween(string $column, $start, $end)
 * @method Queryable orWhereColumn(string $column, string $operator, $value)
 * @method Queryable orWhereIn(string $column, $values)
 * @method Queryable orWhereNotBetween(string $column, $start, $end)
 * @method Queryable orWhereNotIn(string $column, $values)
 * @method Queryable orWhereNotNull(string $column)
 * @method Queryable orWhereNull(string $column)
 * @method Queryable rightJoin(string $fromAlias, string $join, string $alias, string $condition)
 * @method Queryable select(...$columns)
 * @method Queryable setParameter(string $key, $value, ?string|int $type)
 * @method Queryable setParameters(array $parameters)
 * @method Queryable where(string $expression, array $values)
 * @method Queryable whereBetween(string $column, $start, $end)
 * @method Queryable whereColumn(string $column, string $operator, $value)
 * @method Queryable whereIn(string $column, $values)
 * @method Queryable whereNotBetween(string $column, $start, $end)
 * @method Queryable whereNotIn(string $column, $values)
 * @method Queryable whereNotNull(string $column)
 * @method Queryable whereNull(string $column)
 * @method Queryable wherePrimaryKey(int|string $id)
 */
interface Queryable
{

}
