# Read-Models

[![GitHub Actions Build Status](https://github.com/somnambulist-tech/read-models/workflows/tests/badge.svg)](https://github.com/somnambulist-tech/read-models/actions?query=workflow%3Atests)

Read-Models are a companion resource to a Doctrine ORM entity based project. They
provide an active-record style data access layer, designed for presentational
purposes only. This allows your domain objects to remain completely focused on
managing your data and not getting sidelined with presentational concerns.

To further highlight this tight integration, read-models uses DBAL and the DBAL
type system under-the-hood. Any registered types will be used during model hydration
and even embeddables can be reused.

Note that unlike standard active-record packages, there is no write support at all
nor will this be added. This package is purely focused on reading and querying data
with objects / query builders for use in the presentation layer.

A lot of the internal arrangement is heavily inspired by Laravels Eloquent and other
active-record projects including GranadaORM (IdiORM), PHP ActiveRecord and others.

### Current Features

 * active-record query model
 * read-only - no ability to change your db through the built-in methods
 * read-only models - no mutation methods, models are immutable once loaded
 * support for attribute casting
 * support for embedded objects via attribute casting
 * support for exporting as JSON / Array data (configurable)
 * relationships (1:1, 1:m, m:m, 1:m reversed)
 * identity map
 * pluggable attribute casting / cast to value-objects

### Thinking About...

 * doctrine metadata component to use the already available metadata
 * refactor identity map (improve object tracking to prevent stale data / SQL fetches)
 * prevent running / building insert, update, delete queries
 * support composite primary keys?
 * re-implement the query builder engine to allow more complex queries

## Requirements

 * PHP 7.4+
 * mb_string
 * doctrine/dbal
 * somnambulist/collection

## Installation

Install using composer, or checkout / pull the files from github.com.

 * composer require somnambulist/read-models
 * instantiate `Manager` with your connection mappings and any attribute casters
 * add models extending the `Model` class, being sure to set the table name property
 * load some data: `<model>::find()`

For example:

```php
use Somnambulist\ReadModels\Manager;
use Somnambulist\ReadModels\TypeCasters\DoctrineTypeCaster;
use Doctrine\DBAL\DriverManager;

new Manager(
[
    User::class => $conn = DriverManager::getConnection(['url' => 'sqlite://db.sqlite']),
    'default'   => $conn,
],
[
    new DoctrineTypeCaster($conn),
]
);
```

## Usage

Extend `Somnambulist\ReadModels\Model` and add casts, define relationships, exports etc.

```php
class User extends Model
{

    protected string $table = 'users';
}
```

You can add a default table alias by setting the property: `$tableAlias`. Other defaults
can be overridden by defining the property:

```php
class User extends Model
{

    protected string $table = 'tbl_users';
    protected ?string $tableAlias = 'u';
    protected string $primaryKey = 'uuid';

}
```

__Note:__ properties are defined with types and must follow those defined in the base class.

To load a record:

```php
$model = User::find(1);

$results = User::query()->whereColumn('name', 'like', '%bob%')->orderBy('created_at', 'desc')->limit(5)->fetch();
```

Access properties directly or via method calls:

```php
$model = User::find(1);

$model->id;
$model->id();
$model->created_at;
$model->createdAt();
```

You cannot set, unset, or change the returned models.

You can define attribute mutators in the same way as Laravels Eloquent:

```php
class User extends Model
{

    protected function getUsernameAttribute($username)
    {
        return Str::capitalize($username);
    }
}

// user:{username: bob was here} -> returns Bob Was Here

User::find(1)->username();
```

__Note:__ these methods should be `protected` as they expect the current value to be passed
from the loaded model attributes.

Or create virtual properties, that exist at run time:

```php
class User extends Model
{

    protected function getAnniversayDayAttribute()
    {
        return $this->created_at->format('l');
    }
}

// user:{created_at: '2019-07-15 14:23:21'} -> "Monday"

User::find(1)->anniversay_day;
User::find(1)->anniversayDay();
```

Or for micro-optimizations, add the method directly:

```php
class User extends Model
{

    public function anniversayDay()
    {
        return $this->created_at->format('l');
    }
}

// user:{created_at: '2019-07-15 14:23:21'} -> "Monday"

User::find(1)->anniversayDay();
```

__Note:__ to access properties via the magic __get/call the property name must be a valid PHP
property/method name. Keys that start with numbers (for example), will not work. Any virtual
methods / properties should be documented using `@property-read` tags on the class level
docblock comment. Additionally: virtual methods can be tagged using `@method`.

__Note:__ to get a raw attribute value, use `->getRawAttribute()`. This will return null if
the attribute is not found, but could also return null for the specified key.

## More Reading

 * [Upgrading from 1.X to 2.0](docs/upgrading_1.X_to_2.0.md)
 * [Setting up Symfony](docs/setup_symfony.md)
 * [Querying Data](docs/querying.md)
 * [Casting Attributes](docs/casting.md)
 * [Relationships](docs/relationships.md)
 * [Identity Map](docs/identity_map.md)
 * [Exporting Models](docs/exporting.md)

[Auto-generated API docs](docs/api-read-models.md) are available in the docs folder.

## Profiling

If you use Symfony; using the standard Doctrine DBAL connection from your entity manager will
automatically ensure that _ALL_ SQL queries are added to the profiler without having to do
anything else! You get full insight into the query that was executed, the data bound etc.
For further insights consider using an application profiler such as:

 * [Tideways](https://tideways.io)
 * [BlackFire](https://blackfire.io)

For other frameworks; as DBAL is used, hook into the Configuration object and add an SQL
logger instance that can report to your frameworks profiler.

## Test Suite

The test suite uses an SQlite database file named "users.db" that simulates a possible User
setup with Roles, Permissions, Contacts and Addresses. Before running the test suite, be
sure to generate some test data using: `tests/resources/seed.php`. This console app has a
couple of commands:

 * `db:create` - builds the table structure
 * `db:seed` - generate base records and `--records=XX` random records
 * `db:destroy` - deletes all test data and tables

For the test suite to run and be able to test various relationships / eager loading etc a
reasonable number of test records are needed. The suite was built against a random sample
of 150 records.

The `DataGenerator` attempts some amount of random allocation of addresses, contacts and
roles to each user; however data integrity was not the goal, merely usable data. 

To run the tests: `vendor/bin/phpunit`.
