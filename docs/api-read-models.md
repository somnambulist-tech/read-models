## Table of contents

- [\Somnambulist\ReadModels\ModelExporter](#class-somnambulistreadmodelsmodelexporter)
- [\Somnambulist\ReadModels\PaginatorAdapter](#class-somnambulistreadmodelspaginatoradapter)
- [\Somnambulist\ReadModels\FilterGeneratedKeysFromCollection](#class-somnambulistreadmodelsfiltergeneratedkeysfromcollection)
- [\Somnambulist\ReadModels\GenerateRelationshipsToEagerLoad](#class-somnambulistreadmodelsgeneraterelationshipstoeagerload)
- [\Somnambulist\ReadModels\ModelConfigurator](#class-somnambulistreadmodelsmodelconfigurator)
- [\Somnambulist\ReadModels\ModelMetadata](#class-somnambulistreadmodelsmodelmetadata)
- [\Somnambulist\ReadModels\Contracts\AttributeCaster (interface)](#interface-somnambulistreadmodelscontractsattributecaster)
- [\Somnambulist\ReadModels\Contracts\Queryable (interface)](#interface-somnambulistreadmodelscontractsqueryable)
- [\Somnambulist\ReadModels\Contracts\EmbeddableFactory (interface)](#interface-somnambulistreadmodelscontractsembeddablefactory)
- [\Somnambulist\ReadModels\EventSubscriber\IdentityMapClearerSubscriber](#class-somnambulistreadmodelseventsubscriberidentitymapclearersubscriber)
- [\Somnambulist\ReadModels\Exceptions\EntityNotFoundException](#class-somnambulistreadmodelsexceptionsentitynotfoundexception)
- [\Somnambulist\ReadModels\Exceptions\NoResultsException](#class-somnambulistreadmodelsexceptionsnoresultsexception)
- [\Somnambulist\ReadModels\Hydrators\SimpleTypeCaster](#class-somnambulistreadmodelshydratorssimpletypecaster)
- [\Somnambulist\ReadModels\Hydrators\SimpleObjectFactory](#class-somnambulistreadmodelshydratorssimpleobjectfactory)
- [\Somnambulist\ReadModels\Hydrators\DoctrineTypeCaster](#class-somnambulistreadmodelshydratorsdoctrinetypecaster)
- [\Somnambulist\ReadModels\PHPUnit\PHPUnitListener](#class-somnambulistreadmodelsphpunitphpunitlistener)
- [\Somnambulist\ReadModels\Relationships\AbstractRelationship (abstract)](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)
- [\Somnambulist\ReadModels\Relationships\HasOneOrMany (abstract)](#class-somnambulistreadmodelsrelationshipshasoneormany-abstract)
- [\Somnambulist\ReadModels\Utils\ClassHelpers](#class-somnambulistreadmodelsutilsclasshelpers)
- [\Somnambulist\ReadModels\Utils\ProxyTo](#class-somnambulistreadmodelsutilsproxyto)
- [\Somnambulist\ReadModels\Utils\StrConverter](#class-somnambulistreadmodelsutilsstrconverter)

<hr />

### Class: \Somnambulist\ReadModels\ModelExporter

> Class ModelExporter

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\Somnambulist\ReadModels\Model</em> <strong>$model</strong>, <em>array</em> <strong>$attributes=array()</strong>, <em>array</em> <strong>$relationships=array()</strong>)</strong> : <em>void</em><br /><em>Constructor.</em> |
| public | <strong>attributes(</strong><em>mixed</em> <strong>$attributes</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelExporter](#class-somnambulistreadmodelsmodelexporter)</em><br /><em>Export only the specified attributes; if empty will export all attributes</em> |
| public | <strong>toArray()</strong> : <em>array</em><br /><em>Create an array from the model data; including any specified relationships</em> |
| public | <strong>toJson(</strong><em>\int</em> <strong>$options</strong>)</strong> : <em>string</em> |
| public | <strong>with(</strong><em>mixed</em> <strong>$relationship</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelExporter](#class-somnambulistreadmodelsmodelexporter)</em><br /><em>Export with the specified relationships; if empty will NOT export any relationships</em> |

*This class implements \Somnambulist\Collection\Contracts\Jsonable*

<hr />

### Class: \Somnambulist\ReadModels\PaginatorAdapter

> Class PaginatorAdapter

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\Somnambulist\ReadModels\ModelBuilder</em> <strong>$queryBuilder</strong>)</strong> : <em>void</em><br /><em>Constructor.</em> |
| public | <strong>getNbResults()</strong> : <em>mixed</em> |
| public | <strong>getSlice(</strong><em>mixed</em> <strong>$offset</strong>, <em>mixed</em> <strong>$length</strong>)</strong> : <em>mixed</em> |

*This class implements \Pagerfanta\Adapter\AdapterInterface*

<hr />

### Class: \Somnambulist\ReadModels\FilterGeneratedKeysFromCollection

> Class FilterGeneratedAttributesAndKeysFromCollection

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__invoke(</strong><em>array/\Somnambulist\ReadModels\Collection</em> <strong>$attributes</strong>)</strong> : <em>array</em><br /><em>Filters out library generated keys from the set of attributes</em> |

<hr />

### Class: \Somnambulist\ReadModels\GenerateRelationshipsToEagerLoad

> Class GenerateRelationshipsToEagerLoad Encapsulates the logic for generating eager loaded relationships. Based on the eager loading strategy deployed in Laravel Eloquent.

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__invoke(</strong><em>array</em> <strong>$toEagerLoad=array()</strong>, <em>mixed</em> <strong>$relations</strong>)</strong> : <em>array</em><br /><em>Set the relationships that should be eager loaded</em> |

<hr />

### Class: \Somnambulist\ReadModels\ModelConfigurator

> Class ModelConfigurator

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>configure(</strong><em>array</em> <strong>$connections</strong>, <em>\Somnambulist\ReadModels\AttributeCaster/null/[\Somnambulist\ReadModels\Contracts\AttributeCaster](#interface-somnambulistreadmodelscontractsattributecaster)</em> <strong>$caster=null</strong>, <em>\Somnambulist\ReadModels\EmbeddableFactory/null/[\Somnambulist\ReadModels\Contracts\EmbeddableFactory](#interface-somnambulistreadmodelscontractsembeddablefactory)</em> <strong>$factory=null</strong>)</strong> : <em>void</em><br /><em>Configures the base Model settings Connections is an array of Model name (or default) and the DBAL Connection instance $caster and $factory are optional, alternative hydrator instances to set to the Model. Note: while the caster and factor are protected static instances, they should be shared across all models that use the same database.</em> |

<hr />

### Class: \Somnambulist\ReadModels\ModelMetadata

> Class ModelMetadata

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\Somnambulist\ReadModels\Model</em> <strong>$model</strong>, <em>\string</em> <strong>$primaryKey=`'id'`</strong>, <em>\string</em> <strong>$table=null</strong>, <em>\string</em> <strong>$alias=null</strong>, <em>\string</em> <strong>$externalKey=null</strong>, <em>\string</em> <strong>$foreignKey=null</strong>)</strong> : <em>void</em><br /><em>Constructor.</em> |
| public | <strong>externalKeyName()</strong> : <em>void</em> |
| public | <strong>foreignKey()</strong> : <em>string</em><br /><em>Creates a foreign key name from the current class name and primary key name This is used in relationships if a specific foreign key column name is not defined on the relationship.</em> |
| public | <strong>prefixAlias(</strong><em>\string</em> <strong>$column</strong>)</strong> : <em>void</em> |
| public | <strong>primaryKeyName()</strong> : <em>void</em> |
| public | <strong>primaryKeyNameWithAlias()</strong> : <em>void</em> |
| public | <strong>removeAlias(</strong><em>\string</em> <strong>$key</strong>)</strong> : <em>void</em> |
| public | <strong>table()</strong> : <em>void</em> |
| public | <strong>tableAlias()</strong> : <em>void</em> |

<hr />

### Interface: \Somnambulist\ReadModels\Contracts\AttributeCaster

> Interface AttributeCaster

| Visibility | Function |
|:-----------|:---------|
| public | <strong>cast(</strong><em>\Somnambulist\ReadModels\Model</em> <strong>$model</strong>, <em>array</em> <strong>$attributes=array()</strong>, <em>array</em> <strong>$casts=array()</strong>)</strong> : <em>array</em><br /><em>Cast attributes to a type or simple object (or not) Return the modified attributes an array.</em> |

<hr />

### Interface: \Somnambulist\ReadModels\Contracts\Queryable

> Interface Queryable Tags an object as supporting or allowing pass through into the Builder object. This allows for type completion and hints where they might not otherwise exist.

| Visibility | Function |
|:-----------|:---------|

<hr />

### Interface: \Somnambulist\ReadModels\Contracts\EmbeddableFactory

> Interface EmbeddableFactory

| Visibility | Function |
|:-----------|:---------|
| public | <strong>make(</strong><em>array</em> <strong>$attributes</strong>, <em>\string</em> <strong>$class</strong>, <em>array</em> <strong>$args</strong>, <em>\bool</em> <strong>$remove=true</strong>)</strong> : <em>object/null</em><br /><em>Create an object from the attributes including sub-objects See {@see Model::$embeds} for the syntax.</em> |

<hr />

### Class: \Somnambulist\ReadModels\EventSubscriber\IdentityMapClearerSubscriber

> Class IdentityMapClearerSubscriber Kernel subscriber that clears the identity map onRequest start, exception or terminate ensuring that the identity map is fresh for each request. When running under php-fpm this should not be needed; however if you use a PHP application server, that does not terminate, then the identity map will not be cleared between request (e.g. PHP-PM).

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>getSubscribedEvents()</strong> : <em>mixed</em> |
| public | <strong>onException(</strong><em>\Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent</em> <strong>$event</strong>)</strong> : <em>void</em> |
| public | <strong>onRequest(</strong><em>\Symfony\Component\HttpKernel\Event\GetResponseEvent</em> <strong>$event</strong>)</strong> : <em>void</em> |
| public | <strong>onTerminate(</strong><em>\Symfony\Component\HttpKernel\Event\PostResponseEvent</em> <strong>$event</strong>)</strong> : <em>void</em> |

*This class implements \Symfony\Component\EventDispatcher\EventSubscriberInterface*

<hr />

### Class: \Somnambulist\ReadModels\Exceptions\EntityNotFoundException

> Class EntityNotFoundException

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>noMatchingRecordFor(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$key</strong>, <em>string/int</em> <strong>$id</strong>)</strong> : <em>[\Somnambulist\ReadModels\Exceptions\EntityNotFoundException](#class-somnambulistreadmodelsexceptionsentitynotfoundexception)</em> |

*This class extends \Exception*

*This class implements \Throwable*

<hr />

### Class: \Somnambulist\ReadModels\Exceptions\NoResultsException

> Class NoResultsException

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\string</em> <strong>$class</strong>, <em>\Doctrine\DBAL\Query\QueryBuilder</em> <strong>$queryBuilder</strong>)</strong> : <em>void</em><br /><em>Constructor.</em> |
| public | <strong>getQuery()</strong> : <em>\Doctrine\DBAL\Query\QueryBuilder</em> |
| public static | <strong>noResultsForQuery(</strong><em>\string</em> <strong>$class</strong>, <em>\Doctrine\DBAL\Query\QueryBuilder</em> <strong>$queryBuilder</strong>)</strong> : <em>[\Somnambulist\ReadModels\Exceptions\NoResultsException](#class-somnambulistreadmodelsexceptionsnoresultsexception)</em> |

*This class extends \Exception*

*This class implements \Throwable*

<hr />

### Class: \Somnambulist\ReadModels\Hydrators\SimpleTypeCaster

> Class NullTypeCaster

| Visibility | Function |
|:-----------|:---------|
| public | <strong>cast(</strong><em>\Somnambulist\ReadModels\Model</em> <strong>$model</strong>, <em>array</em> <strong>$attributes=array()</strong>, <em>array</em> <strong>$casts=array()</strong>)</strong> : <em>array</em><br /><em>Only removes any table prefixes and performs no other casting</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\AttributeCaster](#interface-somnambulistreadmodelscontractsattributecaster)*

<hr />

### Class: \Somnambulist\ReadModels\Hydrators\SimpleObjectFactory

> Class SimpleObjectFactory

| Visibility | Function |
|:-----------|:---------|
| public | <strong>make(</strong><em>array</em> <strong>$attributes</strong>, <em>\string</em> <strong>$class</strong>, <em>array</em> <strong>$args</strong>, <em>\bool</em> <strong>$remove=true</strong>)</strong> : <em>object/null</em><br /><em>Create an object from the attributes including sub-objects</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\EmbeddableFactory](#interface-somnambulistreadmodelscontractsembeddablefactory)*

<hr />

### Class: \Somnambulist\ReadModels\Hydrators\DoctrineTypeCaster

> Class DoctrineTypeCaster

| Visibility | Function |
|:-----------|:---------|
| public | <strong>cast(</strong><em>\Somnambulist\ReadModels\Model</em> <strong>$model</strong>, <em>array</em> <strong>$attributes=array()</strong>, <em>array</em> <strong>$casts=array()</strong>)</strong> : <em>array</em><br /><em>Cast attributes to a configured Doctrine type through the model connection Casts is an array of attribute name to a valid Doctrine type. The type must be registered to use it. Certain types require a resource and for these `resource:` should be prefixed on the attribute name. This will trigger a conversion of the stream to a resource stream. e.g. for Creof Postgres geo-spatial types. The modified attributes are returned to the caller. Note: Doctrine Types are case-sensitive.</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\AttributeCaster](#interface-somnambulistreadmodelscontractsattributecaster)*

<hr />

### Class: \Somnambulist\ReadModels\PHPUnit\PHPUnitListener

> Class PHPUnitListener Ensures that the identity map is cleared before and after every test case is run. If this is not used, then the identity map will persist across tests, giving false results. Enable it by adding the following to your phpunit.xml file: <code> <extensions> <extension class="Somnambulist\ReadModels\PHPUnit\PHPUnitListener" /> </extensions> </code>

| Visibility | Function |
|:-----------|:---------|
| public | <strong>addError(</strong><em>\PHPUnit\Framework\Test</em> <strong>$test</strong>, <em>\Throwable</em> <strong>$t</strong>, <em>\float</em> <strong>$time</strong>)</strong> : <em>void</em> |
| public | <strong>addFailure(</strong><em>\PHPUnit\Framework\Test</em> <strong>$test</strong>, <em>\PHPUnit\Framework\AssertionFailedError</em> <strong>$e</strong>, <em>\float</em> <strong>$time</strong>)</strong> : <em>void</em> |
| public | <strong>addIncompleteTest(</strong><em>\PHPUnit\Framework\Test</em> <strong>$test</strong>, <em>\Throwable</em> <strong>$t</strong>, <em>\float</em> <strong>$time</strong>)</strong> : <em>void</em> |
| public | <strong>addRiskyTest(</strong><em>\PHPUnit\Framework\Test</em> <strong>$test</strong>, <em>\Throwable</em> <strong>$t</strong>, <em>\float</em> <strong>$time</strong>)</strong> : <em>void</em> |
| public | <strong>addSkippedTest(</strong><em>\PHPUnit\Framework\Test</em> <strong>$test</strong>, <em>\Throwable</em> <strong>$t</strong>, <em>\float</em> <strong>$time</strong>)</strong> : <em>void</em> |
| public | <strong>addWarning(</strong><em>\PHPUnit\Framework\Test</em> <strong>$test</strong>, <em>\PHPUnit\Framework\Warning</em> <strong>$e</strong>, <em>\float</em> <strong>$time</strong>)</strong> : <em>void</em> |
| public | <strong>endTest(</strong><em>\PHPUnit\Framework\Test</em> <strong>$test</strong>, <em>\float</em> <strong>$time</strong>)</strong> : <em>void</em> |
| public | <strong>endTestSuite(</strong><em>\PHPUnit\Framework\TestSuite</em> <strong>$suite</strong>)</strong> : <em>void</em> |
| public | <strong>executeAfterTest(</strong><em>\string</em> <strong>$test</strong>, <em>\float</em> <strong>$time</strong>)</strong> : <em>void</em> |
| public | <strong>executeBeforeTest(</strong><em>\string</em> <strong>$test</strong>)</strong> : <em>void</em> |
| public | <strong>startTest(</strong><em>\PHPUnit\Framework\Test</em> <strong>$test</strong>)</strong> : <em>void</em> |
| public | <strong>startTestSuite(</strong><em>\PHPUnit\Framework\TestSuite</em> <strong>$suite</strong>)</strong> : <em>void</em> |

*This class implements \PHPUnit\Framework\TestListener, \PHPUnit\Runner\BeforeTestHook, \PHPUnit\Runner\Hook, \PHPUnit\Runner\TestHook, \PHPUnit\Runner\AfterTestHook*

<hr />

### Class: \Somnambulist\ReadModels\Relationships\AbstractRelationship (abstract)

> Class AbstractRelationship

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__call(</strong><em>string</em> <strong>$name</strong>, <em>array</em> <strong>$arguments</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)/\Somnambulist\ReadModels\Relationships\ModelBuilder/mixed</em><br /><em>Pass on calls to the builder instance, or return the result of the call</em> |
| public | <strong>__construct(</strong><em>\Somnambulist\ReadModels\ModelBuilder</em> <strong>$builder</strong>, <em>\Somnambulist\ReadModels\Model</em> <strong>$parent</strong>)</strong> : <em>void</em><br /><em>Constructor.</em> |
| public | <strong>addConstraintCallbackToQuery(</strong><em>\callable</em> <strong>$constraint</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Allows a callable to modify the current query before the results are fetched</em> |
| public | <strong>abstract addEagerLoadingConstraints(</strong><em>\Somnambulist\ReadModels\Relationships\Collection/\Somnambulist\Collection\MutableCollection</em> <strong>$models</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Add the constraints required for eager loading a set of results This should initiate a new query to avoid the initialiseRelationship query.</em> |
| public | <strong>addEagerLoadingRelationships(</strong><em>mixed</em> <strong>$relationships</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Adds additional models to eager load to the fetch</em> |
| public | <strong>abstract addEagerLoadingResults(</strong><em>\Somnambulist\ReadModels\Relationships\Collection/\Somnambulist\Collection\MutableCollection</em> <strong>$models</strong>, <em>\string</em> <strong>$relationship</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Executes and maps the eager loaded data to the collection of Models The related models will be inserted in the models relationships array at the key specified by $relationship.</em> |
| public | <strong>fetch()</strong> : <em>\Somnambulist\ReadModels\Relationships\Collection</em><br /><em>Executes the relationship, returning any results</em> |
| public | <strong>getParent()</strong> : <em>\Somnambulist\ReadModels\Model</em><br /><em>Returns the parent (owner) of this relationship</em> |
| public | <strong>getQuery()</strong> : <em>mixed</em> |
| public | <strong>getRelated()</strong> : <em>\Somnambulist\ReadModels\Model</em><br /><em>Returns the related object (the current subject) of this relationship</em> |
| public | <strong>hasMany()</strong> : <em>bool</em><br /><em>True if there can be many results from this relationship</em> |
| protected | <strong>abstract initialiseRelationship()</strong> : <em>void</em><br /><em>Apply the base single model constraints to the main query</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\Queryable](#interface-somnambulistreadmodelscontractsqueryable)*

<hr />

### Class: \Somnambulist\ReadModels\Relationships\HasOneOrMany (abstract)

> Class HasOneOrMany

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\Somnambulist\ReadModels\ModelBuilder</em> <strong>$builder</strong>, <em>\Somnambulist\ReadModels\Model</em> <strong>$parent</strong>, <em>\string</em> <strong>$foreignKey</strong>, <em>\string</em> <strong>$localKey</strong>)</strong> : <em>void</em><br /><em>Constructor.</em> |
| public | <strong>addEagerLoadingConstraints(</strong><em>\Somnambulist\Collection\MutableCollection</em> <strong>$models</strong>)</strong> : <em>void</em> |
| protected | <strong>initialiseRelationship()</strong> : <em>void</em> |

*This class extends [\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)*

*This class implements [\Somnambulist\ReadModels\Contracts\Queryable](#interface-somnambulistreadmodelscontractsqueryable)*

<hr />

### Class: \Somnambulist\ReadModels\Utils\ClassHelpers

> Class Helpers Assorted helpers, scoped to prevent global functions.

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>getCallingClass()</strong> : <em>string</em><br /><em>Returns the class that made a call to the current class</em> |
| public static | <strong>getCallingMethod()</strong> : <em>string</em><br /><em>Returns the function (method name) that called the function this is used in</em> |
| public static | <strong>getObjectShortClassName(</strong><em>\object</em> <strong>$object</strong>)</strong> : <em>string</em> |
| public static | <strong>set(</strong><em>\object</em> <strong>$object</strong>, <em>\string</em> <strong>$property</strong>, <em>mixed</em> <strong>$value</strong>, <em>null/string/object</em> <strong>$scope=null</strong>)</strong> : <em>object</em><br /><em>Set the property in object to value If scope is set to a parent class, private properties can be updated.</em> |
| public static | <strong>setPropertyArrayKey(</strong><em>\object</em> <strong>$object</strong>, <em>\string</em> <strong>$property</strong>, <em>\string</em> <strong>$key</strong>, <em>mixed</em> <strong>$value</strong>, <em>null/string/object</em> <strong>$scope=null</strong>)</strong> : <em>object</em><br /><em>Set an array key in the object property to value If scope is set to a parent class, private properties can be updated.</em> |

<hr />

### Class: \Somnambulist\ReadModels\Utils\ProxyTo

> Class ProxyTo Based on the Laravel trait ForwardsCalls

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__invoke(</strong><em>\object</em> <strong>$object</strong>, <em>\string</em> <strong>$method</strong>, <em>array</em> <strong>$parameters=array()</strong>)</strong> : <em>mixed</em><br /><em>Proxy a call into the specified object, collecting error information if it fails</em> |

<hr />

### Class: \Somnambulist\ReadModels\Utils\StrConverter

> Class StrConverter

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>toResource(</strong><em>\string</em> <strong>$string</strong>)</strong> : <em>bool/\Somnambulist\ReadModels\Utils\resource</em><br /><em>Converts a string to a stream resource by passing it through a memory file</em> |

