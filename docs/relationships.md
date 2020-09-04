
## Relationships

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
The benefit of a protected relationship, is fewer exposed methods and not confusing other devs
with both a property and method for the same thing.

### Using the Relationship

Relationships can operate in two ways:

 * Returning the relationship object that represents the linked data allowing it to be queried,
 * Returning the actual, loaded, objects by executing the default relationship query.

To access the relationship object, call the method that defines it - provided it is public.

For example: to access the roles relationship and modify the query:

```php
User::find(1)->roles()->select(...)...->fetch();
```

By accessing the relationship object you have full control over the query that will be executed
and can limit what is selected, or add conditionals etc. This method will require explicitly
calling `->fetch()` to actually run the query.

To fetch and load the related models immediately call the relationship name as a property. This
will execute the underlying query object and return either a Collection instance or the object.
The return type depends on the relationship that was defined. One to one relationships or 1:m
inversed, will always return the mapped object. One to many or many to many will always produce
a Collection of objects.

For 1:1 and 1:m reversed, they can be configured to optionally return an empty object if there
is no result e.g.: A User was disconnected from an Account but this is valid, so to prevent
calls to null, the `account()` relationship could be set to not return null allowing chaining to
an empty Account object.

For example: to fetch the first Role of the mapped Roles to a User:

```php
User::find(1)->roles->first();
```

The call to `->first()` returns the first object from the loaded Collection and is being run
from the Collection object. Under the hood, the attribute accessor accessed the relationship
method, fetching the rules and executing the default query, returning the collection and mapping
it into the parent model.

__Note:__ calls into relationships are always eager i.e.: all records will be returned without
any limits. Further: if default includes are defined, cascade queries may be issued. It is not
recommended to perform this step in loops as it can lead to n+1 queries. To better optimise use
eager loading to reduce the queries needed.

### Eager Load Relationships

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
the keys are loaded so that the models can be attached to the user.

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

__Note:__ the join-table is required for many-to-many relationships. It is not guessed.
