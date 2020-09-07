
## Casting Data

Read models utilises an extended `AttributeCaster` system to convert scalar types to
objects. Several casters are provided including a `DoctrineTypeCaster`. Using the
`DoctrineTypeCaster` allows directly casting to any known (registered) DBAL type.
Add the doctrine type to the casts array for the attribute:

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

__Note:__ types requiring converting to a resource may require explicitly registering by
calling either:

 * `Manager::instance()->caster()->add($doctrineCaster, ['resource:...'])` or,
 * `Manager::instance()->caster()->extend('geometry', ['resource:...'])`

### Embeddables / Casting to Value Objects

Just like Doctrine ORM, you can embed and hydrate value-objects into the read models.
These can be the exact same VOs used in the main domain (this is safe, VOs are immutable).
Like with Doctrine, these are mapped as types against the attribute name you want the resulting
VO to be loaded to:

```php
class UserContact extends Model
{

    protected $casts = [
        'contact' => Contact::class, // or 'contact' if that alias was registered
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

To cast this a custom `AttributeCaster` is needed. All casters must implement the interface
to be registered in the Managers master caster system. For the above `Contact` VO, the caster
could be:

```php
use Somnambulist\Components\ReadModels\Contracts\AttributeCaster;

class ContactCaster implements AttributeCaster
{
    public function types(): array
    {
        return ['contact', Contact::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, string $attribute, string $type): void
    {
        $attributes['contact'] = new Contact(
            $attributes['name'],
            $attributes['contact_phone'] ? new PhoneNumber($attributes['contact_phone']) : null,
            $attributes['contact_email'] ? new EmailAddress($attributes['contact_email']) : null,
        );

        unset($attributes['name'], $attributes['contact_phone'], $attributes['contact_email']);
    }
}
``` 

When the `Contact` is created, the `name`, `contact_phone` and `contact_email` attributes
are used to build the value-object. This is then set to the key `contact`. As the attributes
array is passed by reference, the original attributes can be removed (`unset()`), though this
will mean other casters will not be able to access them.

It is recommended to guard against empty attribute arrays, or where the attributes only contain
partial results.

The `types()` method is used to pre-register the names that the caster will respond to. Any
number of casters can be registered, however the type name must be unique. If you require
variations use either class names, or prefixed names to distinguish.
 
The result is something like:

```text
...
  #attributes: array:3 [
    "id" => 1
    "user_id" => 1
    "contact" => Contact^ {#54
      -name: "A Contact"
      -phone: Somnambulist\Domain\Entities\Types\PhoneNumber^ {#138
        -value: "+12345678901"
      }
      -email: Somnambulist\Domain\Entities\Types\Identity\EmailAddress^ {#139
        -value: "a.contact@example.com"
      }
    }
  }
...
```

## Built-in Casters

The following built-in casters are available:

 * `AreaCaster` - cast to an `Area` value-object (requires: somnambulist/domain)
 * `CoordinateCaster` - cast to a `Coordinate` value-object (requires: somnambulist/domain)
 * `DistanceCaster` - cast to an `Area` value-object (requires: somnambulist/domain)
 * `DoctrineTypeCaster` - cast to any register Doctrine type
 * `ExternalIdentityCaster` - cast to an `ExternalIdentity` value-object (requires: somnambulist/domain)
 * `MoneyCaster` - cast to a `Money` value-object (requires: somnambulist/domain)

The built-in casters can be registered in a DI container for re-use.
