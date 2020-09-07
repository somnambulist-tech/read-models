
## Identity Map

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
process be sure to periodically call `Manager::instance()->map()->clear()`.

The Identity Map can be accessed from the `Manager`.

For Symfony based projects there are 2 kernel event listeners that can be subscribed:

 * `IdentityMapClearerSubscriber` clears the map on request / error
 * `IdentityMapClearerMessengerSubscriber` clears the map after handling messages in Messenger

### IdentityMap Test Listener

When working with PHPUnit the identity map will preserve loading entities across tests unless
cleared. A listener is included that will clear the identity map before and after every test
case for you.

Add the following to your `phpunit.xml` config file to enable the automatic clearing:

```xml
<phpunit>
    <!-- other unit config excluded -->
    
    <extensions>
        <extension class="Somnambulist\Components\ReadModels\PHPUnit\PHPUnitListener"/>
    </extensions>
</phpunit>
```

In addition: if you do not use a dependency injection container in your tests, you may need to
setup a bootstrap that will configure the `Manager` with suitable defaults. Remember: the
`Manager` must be instantiated before it can be accessed statically.
