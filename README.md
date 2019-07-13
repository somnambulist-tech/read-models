# Read-Models

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
 * support for attribute casting
 * support for embeddables
 * support for exporting as JSON / Array data (configurable)
 * relationships (1:1, 1:m, m:m, 1:m reversed)
 * identity map
 * pluggable attribute / embeddable hydrators

__Note:__ this library is at an early stage of development and there may be large API
changes between releases.

### Thinking About Adding...

 * using relationship `with` syntax for the export component
 * doctrine metadata component to use the already available metadata
 * better way of handling relationship mapping
 * refactor integration of identity map
 * consider reducing the scope of the builder component
 * prevent running / building insert, update, delete queries

## Requirements

 * PHP 7.2+
 * mb_string
 * doctrine/dbal
 * somnambulist/collection

## Installation

Install using composer, or checkout / pull the files from github.com.

 * composer require somnambulist/read-models
 * bind a DBAL Connection instance to `Model` or a specific connection per model type

### Symfony Setup

In a Symfony project create a new Bundle class and register it in your `bundles.php`
file or in your `Kernel` class. In the `boot` method, bind the connections you need
to the Model or Model types. At this stage you can additionally replace the
AttributeCaster or EmbeddableFactory. The caster and factory could be created as
services if you wished to inject other dependencies into them.

The boot method of the bundle then needs:

```php
class MyBundle extends Bundle
{

    public function boot()
    {
        Model::bindConnection($this->container->get('doctrine.dbal.default_connection'));
    }
}
```

Add calls to `bindAttributeCaster` and `bindEmbeddableFactory` if needed or use the
`ModelConfigurator::configure()` call instead that will setup multiple connections and
optional the caster / factory if specified.

__Note:__ as the Model uses static binding for the connections, it is not possible to
perform this step in the services configuration. If you do attempt this and the
connection is not setting, it is because the configurator or factory calls were compiled
out of the container as there would be no service calls to it.

#### Kernel Subscriber

If running under PHP-PM or another app-server that keeps the kernel running, then the
kernel subscriber should be registered to ensure the identity map is flushed between
requests.

```yaml
services:
    Somnambulist\ReadModels\EventSubscriber\IdentityMapClearerSubscriber:
        public: false
```

This will run:

 * onRequest (priority 255)
 * onException (priority -255)
 * onTerminate (priority -255)

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

[Auto-generated API docs](docs/api-read-models.md) are avaiable in the docs folder.

### Identity Map

Read-Models uses an identity map to ensure that you only ever have one instance, and the same
instance of the object. Right now this is a very simple implementation that does not deal with
updating the shared instance, so once loaded it is whatever the original data is. A future
update may change this.

The identity map is used to resolve and track relationships between models and provides an
aliasing system to match foreign key names to a class name.

Because of the identity map, it means you can perform object comparisons without needing to use
an equality method; User object with id 1 will be the same as a reloaded User object with id 1.

There is a slight penalty during hydration for the lookups, however this does result in far
fewer objects in memory at any one time (for example a User with permissions).

The identity map does need clearing at the end of a request and if running in a long running
process be sure to periodically call `->clear()`.

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

### Embeddables

Just like Doctrine ORM, you can embed and hydrate value-objects into the read models.
These can be the exact same VOs used in the main domain (this is safe, VOs are immutable).
Like with Doctrine, this is mapped out but as an array:

```php
class UserContact extends Model
{

    protected $embeds = [
        'contact' => [
            Contact::class, [
                'contact_name',
                [PhoneNumber::class, ['contact_phone_number']],
                [EmailAddress::class, ['contact_email']],
            ]
        ]
    ];
}
```

The Contact class has the following signature:

```php
class Contact
{

    public function __construct($name, ?PhoneNumber $phone, ?EmailAddress $email)
    {
    }
}
```

During hydration, contact_name, contact_phone_number and contact_email will be converted
to constructor parameters for the Contact class. If this fails i.e. the count of the
params does not match the defined args; null will be returned. Otherwise, the Contact
class will be instantiated with the mapped objects / string.

The definition must match the constructor argument order and the parameters will be built
in that order and passed as-is to the constructor.

In addition to calling a new instance, a factory method can be used:

```php
class UserAddress extends Model
{
    protected $embeds = [
        'country' => ['Somnambulist\ValueObjects\Types\Geography\Country::create', ['country']]
    ];
}
```

A `Country` enumerable will be created from country field and re-assigned to the same property.

The result is something like:

```text
...
  #attributes: array:14 [
    "id" => 1
    "user_id" => 1
    "country" => Somnambulist\ValueObjects\Types\Geography\Country^ {#54
      -name: "United Kingdom"
      -code: Somnambulist\ValueObjects\Types\Geography\CountryCode^ {#138
        -value: "GBR"
        -key: "GBR"
      }
    }
  }
...
```

For optional values e.g.: an `Address` may have optional properties, prefix the property name
with a `?`. For example: a contact may need one of email or phone:

```php
class UserContact extends Model
{

    protected $embeds = [
        'contact' => [
            Contact::class, [
                'contact_name',
                [PhoneNumber::class, ['?contact_phone_number']],
                [EmailAddress::class, ['?contact_email']],
            ]
        ]
    ];
}
```
The `Contact` class accepts nulls for both value-objects so if there is no value (it must be
a null value - false, empty string or 0 are considered to be values) null will be passed and
the contact can still be created.

To keep the model clean, you can specify a third parameter in the array: `true`. This will
remove the elements from the attributes after successful conversion to an embeddable. The
default is always false - you have to explicitly opt-in.

### Custom Hydrators

Both the attribute casting and the embeddable building can be switched for another implementation.
They are injected via an interface. This can be done per model via the static calls to:

 * `User::bindAttributeCaster($myCaster)`
 * `User::bindEmbeddableFactory($myFactory)`
 
Or to switch all, use the `Model::bind....()` and all instances will use that version.

Note: the identity map cannot be switched out and is global across all Models - otherwise it
won't work.

### Relationships

Define a relationship between models by adding a method named for that relationship.
For example: A User has many Roles:

```php
class User extends Models
{

    public function roles()
    {
        return $this->hasMany(Role:class);
    }
}
```

If you leave the method public, the relationship can be directly accessed for method chaining,
however you can make it protected and still access the relationship using: getRelationship().
The benefit of a protected relationship, is fewer exposed methods.

To access the related models:

```php
User::find(1)->roles->first();
```

The relationship will return a `Collection` object where there are many or the object itself
for 1:1 and belongsTo.

#### Eager Load Relationships

As read-models is based on Eloquent and how that operates, you can eager load models in the
same way! Either define the property to always load data, or start with a `with` call.

```php
$users = User::with('roles.permissions')->whereColumn('name', 'LIKE', '%bob')->limit(3)->fetch();
```

Will fetch the users, the roles and the permissions for the roles. And just like Eloquent you
can also specify specific fields:

```php
$users = User::with('roles:name.permissions:name')->whereColumn('name', 'LIKE', '%bob')->limit(3)->fetch();
```

Will only load the name of the role and the permission... except! Read-Models will ensure that
the keys are also loaded so that the models can be attached to the user.

Currently read-models supports:

 * one-to-one (hasOne)
 * one-to-many (hasMany)
 * many-to-many (belongsToMany)
 * one-to-many inverse (belongsTo)
 
The table and foreign field names can be customised or leave the library to attempt to guess
at them.

As Doctrine does not support `through` type relationships, these are not implemented. An
additional note: there is no notion of a `pivot` table. An un-typed intermediary table like
this is a sign your data domain is missing an object participant and this should be
implemented. Again, Doctrine does not allow this type of table structure.

__Note:__ for join tables on many-to-many; these are always presumed to be named as singular
source table name `_` and plural target table name. For example: a User has many Roles, the
join table is auto-generated as `user_roles`.

### Exporting

The models can be exported as an array or JSON by calling into the exporter: `->export()` or
using `->jsonSerialize()`. Using `export()` allows fine-grained control over what is being
exported.

Additionally: a default export scheme can be set by overriding the `$exports` key and setting
the attributes and relationships that should be exported by default.The attribute keys can be
overridden for example: to not export surrogate keys / to export a UUID as the primary id.

For example:

```php
class User extends Model
{
    protected $exports = [
        'attributes'    => ['uuid' => 'id', 'name', 'date_of_birth',],
        'relationships' => ['addresses', 'contacts'],
    ];
}
```

This will export the UUID field as `id` along with the name and date_of_birth. Any addresses
and contacts will be exported, using whatever rules are defined on those.

Note: the export rules can be overridden, but it is on a per object basis. If you wish to
apply the same rules to a collection of objects, use the collection methods to foreach over
the models and apply the rules to all items.

The exporter will only touch attributes and relationships; no other properties. For attributes
it will convert objects to arrays / strings if possible, however it currently cannot access
private inherited properties.

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
