
## Exporting Models

The models can be exported as an array or JSON by calling into the exporter: `->export()` or
using `->jsonSerialize()`. Using `export()` allows fine-grained control over what is being
exported.

Additionally: a default export scheme can be set by overriding the `$exports` key and setting
the attributes and relationships that should be exported by default. The attribute keys can be
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

__Note:__ the export rules can be overridden, but it is on a per object basis. If you wish to
apply the same rules to a collection of objects, use the collection methods to foreach over
the models and apply the rules to all items.

The exporter will only touch attributes and relationships; no other properties. For attributes
it will convert objects to arrays / strings if possible, however it currently cannot access
private inherited properties.

### Export Relationship Attributes

Just like with eager loading; specific attributes can be exported via the relationships, and
just like eager loading the syntax is the same. Add a colon and then comma separate the
attributes you wish to export from that relationship. For collections, these will be passed
to all Models in that collection.

For example:

```php
User::find(1)->export()->include('roles:name.permissions:name')->toArray();
```

This will export the User with all roles but only the name and all permissions per role but
only the permission name.

These rules can be defined on the default exports too.
