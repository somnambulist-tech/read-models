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

`where` will accept a callback as the expression. This will be passed the current `ModelBuilder`
as the only argument.

### Correlated Sub-Queries

It is possible to pass ModelBuilder instances to the `select()` method. This will add the builder
as a `SELECT (query) AS ...`. When using this form, the second argument is the alias. If not set
then `sub_select_(n+1)` will be used as a placeholder.

Note that when using this, you still need to actively select the columns e.g. `select('*')`
either before or after `select($builder)`, otherwise no fields will be selected when the query
is finally run - apart from the sub-select.

`select()` can accept a callback. It will be provided the current `ModelBuilder` as the only
argument. You can manipulate the query however you like in the callback. Similar to using a 
builder instance, you still need to select columns as the callback will not trigger any defaults
to be added.

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

## Model Scopes

From 2.0.0 local scopes can be defined on the Model. A scope provides a convenient way to add
re-usable query parts. To add a scope create a method that starts with `scope`. It will be
passed a `ModelBuilder` instance and then arguments that are needed. For example; on a `User`
model it may be useful to have quick methods for fetching only active users:

```php
<?php

class User extends Model
{

    public function scopeOnlyActive(ModelBuilder $builder)
    {
        $builder->whereColumn('is_active', '=', 1);
    }
}
```

To use the scope when querying for objects, add it without the `scope` prefix on the `query` call:

```php
$users = User::query()->onlyActive()->fetch();
```

Arguments can be passed in as well and type hinted:

```php
<?php

class User extends Model
{

    public function scopeActiveIs(ModelBuilder $builder, bool $state)
    {
        $builder->whereColumn('is_active', '=', (int)$state);
    }
}

$users = User::query()->activeIs(false)->fetch();
```
