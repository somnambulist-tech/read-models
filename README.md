# Read-Models

Read-Models are a companion resource to a Doctrine ORM entity based project. They
provide an active-record style data access layer, designed for presentational
purposes only. This allows your domain objects to remain completely focused on
managing your data.

To further highlight this tight integration, read-models uses DBAL and the DBAL
type system under-the-hood. Any registered types will be used during model hydration
(embeddable support is planned).

Note that unlike standard active-record packages, there is no write support at all
nor will this be added. This package is purely focused on reading and querying data
with objects / query builders.

A lot of the internal arrangement is heavily inspired by Laravels Eloquent and other
active-record projects including GranadaORM, PHP ActiveRecord and others.

__Note:__ this library is at a **very** early stage of development.

### Requirements

 * PHP 7.2+
 * mb_string
 * doctrine/dbal
 * somnambulist/collection

### Installation

Install using composer, or checkout / pull the files from github.com.

 * composer require somnambulist/read-models
 * bind a DBAL Connection instance to `Model` or a specific connection per model type

## Usage

Extend `Somnambulist\ReadModels\Model` and add casts, and define relationships.

```php
class User extends Model
{

}
```

You can add a default table alias by setting the property: `$tableAlias`. Other defaults
can be overridden by defining the property:


```php
class User extends Model
{

    protected $tableAlias = 'u'
    protected $table = 'tbl_users',
    protected $primaryKey = 'uuid';

}
```

Then to load a record:

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

### Casting Data

To cast to a known (registered) DBAL type, add key/value pairs to the casts array:

```php
class User extends Model
{

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'uuid' => 'uuid',
        'website_url' => 'url',
        'location' => 'resource:geometry',
    ];
}
```

Certain DBAL types expect a resource to work with (e.g. Creof GeoSpatial Postgres types).
Prefix the type with `resource:` and the string will be converted to a resource and passed
through.

### Relationships

Define a relationship between models by adding a method named for that relationship.
For example: A User has many Roles:

```php
class User extends Models
{

    public function roles()
    {
        return $this->>hasMany(Role:class);
    }
}
```

If you leave the method public, the relationship can be directly accessed for method chaining,
however you can make it protected and still access the relationship using: getRelationship().
The benefit of a protected relationship, is fewer exposed methods.

Currently read-models supports:

 * one-to-one (hasOne)
 * one-to-many (hasMany)
 * many-to-many (belongsToMany)

