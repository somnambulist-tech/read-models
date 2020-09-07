
## Symfony Setup

A Symfony bundle is available that provides default services and casters. A config
file is needed to set the connection mappings, otherwise the default DBAL connection
will be mapped to the "default" connection.

If using this package without the bundle then:

 * add the `Somnambulist\Components\ReadModels\Manager` as a service to your `services.yaml`
 * define the connection map (required); at least `default: '@doctrine.dbal.default_connection'`
 * add casters that are tagged with `somnambulist.read_models.type_caster`
 * add `!tagged_iterator somnambulist.read_models.type_caster` to the `Manager` service
 * add the `IdentityMapClearerSubscriber` to flush the identity map

An example is in the tests:

```yaml
services:
    Somnambulist\Components\ReadModels\EventSubscribers\IdentityMapClearerSubscriber:

    Somnambulist\Components\ReadModels\Manager:
        public: true
        arguments:
            $connections:
                'default': '@doctrine.dbal.default_connection'
            $casters: !tagged_iterator somnambulist.read_models.caster

    Somnambulist\Components\ReadModels\TypeCasters\:
        resource: '../vendor/somanmbulist/read-models/src/TypeCasters/'
        tags: ['somnambulist.read_models.caster']

    App\Models\TypeCasters\:
        resource: '../src/Models/TypeCasters/'
        tags: ['somnambulist.read_models.caster']

```

If using Symfony Messenger, there is a separate subscriber that will clear the `IdentityMap`
between handling messages. Add this to the services if needed.

### Kernel Subscriber

If running under PHP-PM or another app-server that keeps the kernel running, then the
kernel subscriber should be registered to ensure the identity map is flushed between
requests.

```yaml
services:
    Somnambulist\Components\ReadModels\EventSubscribers\IdentityMapClearerSubscriber:
        public: false
```

This will run:

 * onRequest (priority 255)
 * onException (priority -255)
 * onTerminate (priority -255)

### Messenger Subscriber

If running under a long running message consumer that uses read-models, it may be
necessary to keep the identity map cleared between messages. A subscriber is included
to do that:

```yaml
services:
    Somnambulist\Components\ReadModels\EventSubscribers\IdentityMapClearerMessnegerSubscriber:
        public: false
```

This will run:

 * onWorkerMessageHandled
 * onWorkerMessageFailed
