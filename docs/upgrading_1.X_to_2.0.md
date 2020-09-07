
## Upgrading from 1.X to 2.0

There have been a number of changes introduced with 2.0 with the largest being:

 * PHP 7.4+ is required
 * namespace change to `Somnambulist\Components\ReadModels`
 * removal of static caster, connections and embeddable factory
 * removal of embeds property
 * removal of embeddable factory in favour of attribute casters
 * table name is required
 * property type-hinting on all property definitions

### Namespace

The previous `Somnambulist\ReadModels` namespace has been changed to `Somnambulist\Components\ReadModels`.
Any references should be updated.

### Manager Object

Introduced with 2.0 is a `Manager` object that allows configuring the connections and casters.
This is a singleton, static service, however the instance is set on instantiating allowing it
to be used with dependency injection containers.

Previously where the abstract `Model` class would require connection / caster binding, this is
now done on the manager instead.

The `Manager` must be instantiated before any Model access method can be used.

For Symfony, in 1.X it was necessary to do the following setup in a bundle boot method:

```php
class MyBundle extends Bundle
{

    public function boot()
    {
        Model::bindConnection($this->container->get('doctrine.dbal.default_connection'));
    }
}
```

in 2.0 this can either be done entirely in `services.yaml` by setting up the `Manager` as
a service or it can be set in the same boot method:

```php
use Somnambulist\Components\ReadModels\Manager;
use Somnambulist\Components\ReadModels\TypeCasters\DoctrineTypeCaster;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MyBundle extends Bundle
{

    public function boot()
    {
        new Manager([
            'default' => $conn = $this->container->get('doctrine.dbal.default_connection'),
        ], [
            new DoctrineTypeCaster($conn),
        ]);
    }
}
```

A Symfony bundle has been made to make it easier to integrate read-models.

__Note:__ the `Manager` requires setting up for any unit testing.

__Note:__ additional type casters are available in the [attribute-model](https://github.com/somnambulist-tech/attribute-model) library.

### Table Name Property

read-models depends on Doctrine for all the underlying functionality. Once of the large changes
Doctrine made is to the Inflector. It is now intended to be a service and not accessible
statically. While it could be re-integrated, the decision was taken to remove it completely as
typically the model classes do not follow the standard naming conventions.

For example: read models are intended to be presenters / view models, so a typical use case would
be naming the User `UserView` or `UserPresenter`. This requires explicitly setting the table name
for it to work as it cannot be guessed, negating the need for the inflector.

### Casting Embedded Objects

The previous array definitions for building objects, while working, could only handle very
simple use-cases, further this required two separate systems that are really doing the same
job.

In 2.0 the `EmbeddableFactory` has been removed in favour of custom casters for value objects.
Previously to hydrate a `Contact` value-object the following embed was required:

```php
protected $embeds = [
    'contact' => [
        Contact::class, [
            'contact_name',
            [PhoneNumber::class, ['?contact_phone_number']],
            [EmailAddress::class, ['?contact_email']],
        ]
    ]
];
```

Now to cast this, a custom caster is needed that will set the "contact" attribute to the contact
instance. The caster can then decide to remove the attributes or not. See the [casting](casting.md)
documentation for more details and an example.

For v2 each of the embeds will need converting to a caster. A side effect is that duplicate
code will now be re-usable. Several built-in casters are available including a `DoctrineTypeCaster`.

__Note:__ by default *no* casters are registered with the `Manager`.

### EventSubscriber namespace

`EventSubscriber` has been renamed to `EventSubscribers`. Any reference will need changing.
