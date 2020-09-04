## Querying Data

Like with many other ActiveRecord implementation, underlying the Model is a ModelBuilder that
wraps the standard Doctrine DBAL QueryBuilder with some convenience methods. The underlying
query can be accessed to allow for even more complex queries, however you should consider
using straight SQL at that point to fetch the primary ID's and then loading models from those
IDs after the fact.

All queries will start with either: `with()` or `query()`. The following methods are available:

 * `andHaving`
 * `count` returns the count of the query at this point
 * `expression` returns the DBAL ExpressionBuilder
 * `fetch` runs the current query, returning results
 * `fetchFirstOrFail` runs the current query, returning the first result or throwing an exception
 * `fetchFirstOrNull` runs the current query, returning the first result or null
 * `findBy` returns a Collection matching the criteria
 * `findOneBy` returns the first result matching the criteria or null
 * `groupBy`
 * `having`
 * `innerJoin`
 * `join`
 * `leftJoin`
 * `limit` set the maximum results per page
 * `offset` set the start of the results
 * `orderBy`
 * `orHaving`
 * `orWhere` add an arbitrarily complex `OR <expression>` clause including multiple values
 * `orWhereBetween` add an `OR <column> BETWEEN <start> AND <end>` clause
 * `orWhereColumn`
 * `orWhereIn`
 * `orWhereNotBetween`
 * `orWhereNotIn`
 * `orWhereNotNull`
 * `orWhereNull`
 * `rightJoin`
 * `select` select specific columns or add additional properties (see [Select Notes](#Select Notes))
 * `where` add an arbitrarily complex `AND <expression>` clause including multiple values
 * `whereBetween` add an `AND <column> BETWEEN <start> AND <end>` clause
 * `whereColumn`
 * `whereIn`
 * `whereNotBetween`
 * `whereNotIn`
 * `whereNotNull`
 * `whereNull`
 * `wherePrimaryKey` specifically search for the primary key with the specified value

These methods can be chained together, the underlying DBAL QueryBuilder called to add more
complex expressions.

__Note:__ the query builder does not produce "optimised" SQL. It is dependent on the developer
to profile and test the generated SQL queries. Certain types of query (nested sub-selects or
dependent WHERE clauses) may not perform very well.

__Note:__ `where` and `orWhere` are not for basic column queries. These methods are for custom
SQL that must contain named placeholders with an array of values. Use `whereColumn` for a basic
column query. These methods allow you to create complex nested WHERE criteria or use sub-selects
etc and add that SQL to your query.

### `findBy` and `findOneBy`

From 1.1.0 `findBy` and `findOneBy` have been added to the `ModelBuilder`. These allow for basic
`AND WHERE x = y` type queries that will return multiple or one result. The methods have the
same signature as the Doctrine EntityRepository (except orderBy defaults to an empty array).
Use them when you wish to quickly find record(s) with simple criteria:

```php
// return the first 10 matches where user is_active=1 AND email=bob@example.org
// ordered by created_at desc

$results = User::query()->findBy(['is_active' => 1, 'email' => 'bob@example.org'], ['created_at' => 'DESC'], 10);
```

### Select Notes

When changing the selected columns, bear in mind that the identity map will return the same
instance and that instance is the first loaded instance. If you only load a couple of attributes
then you may have issues later on. Additionally: some logic may require or be dependent on the
full model being loaded e.g.: virtual properties.

For relationships, the required keys to setup and query that relationship will be automatically
added to any query to ensure it can still function. This may not work in all cases, so ensure
that you have sufficient tests for any data fetches.
