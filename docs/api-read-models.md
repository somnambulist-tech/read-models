## Table of contents

- [\Somnambulist\Components\ReadModels\ModelExporter](#class-somnambulistcomponentsreadmodelsmodelexporter)
- [\Somnambulist\Components\ReadModels\PaginatorAdapter](#class-somnambulistcomponentsreadmodelspaginatoradapter)
- [\Somnambulist\Components\ReadModels\Model (abstract)](#class-somnambulistcomponentsreadmodelsmodel-abstract)
- [\Somnambulist\Components\ReadModels\ModelMetadata](#class-somnambulistcomponentsreadmodelsmodelmetadata)
- [\Somnambulist\Components\ReadModels\ConnectionManager](#class-somnambulistcomponentsreadmodelsconnectionmanager)
- [\Somnambulist\Components\ReadModels\Contracts\Queryable (interface)](#interface-somnambulistcomponentsreadmodelscontractsqueryable)
- [\Somnambulist\Components\ReadModels\EventSubscribers\IdentityMapClearerMessengerSubscriber](#class-somnambulistcomponentsreadmodelseventsubscribersidentitymapclearermessengersubscriber)
- [\Somnambulist\Components\ReadModels\EventSubscribers\IdentityMapClearerSubscriber](#class-somnambulistcomponentsreadmodelseventsubscribersidentitymapclearersubscriber)
- [\Somnambulist\Components\ReadModels\Exceptions\EntityNotFoundException](#class-somnambulistcomponentsreadmodelsexceptionsentitynotfoundexception)
- [\Somnambulist\Components\ReadModels\Exceptions\NoResultsException](#class-somnambulistcomponentsreadmodelsexceptionsnoresultsexception)
- [\Somnambulist\Components\ReadModels\Exceptions\ConnectionManagerException](#class-somnambulistcomponentsreadmodelsexceptionsconnectionmanagerexception)
- [\Somnambulist\Components\ReadModels\PHPUnit\PHPUnitListener](#class-somnambulistcomponentsreadmodelsphpunitphpunitlistener)
- [\Somnambulist\Components\ReadModels\Relationships\AbstractRelationship (abstract)](#class-somnambulistcomponentsreadmodelsrelationshipsabstractrelationship-abstract)
- [\Somnambulist\Components\ReadModels\Relationships\HasOneOrMany (abstract)](#class-somnambulistcomponentsreadmodelsrelationshipshasoneormany-abstract)
- [\Somnambulist\Components\ReadModels\TypeCasters\DoctrineTypeCaster](#class-somnambulistcomponentsreadmodelstypecastersdoctrinetypecaster)
- [\Somnambulist\Components\ReadModels\Utils\ClassHelpers](#class-somnambulistcomponentsreadmodelsutilsclasshelpers)
- [\Somnambulist\Components\ReadModels\Utils\FilterGeneratedKeysFromCollection](#class-somnambulistcomponentsreadmodelsutilsfiltergeneratedkeysfromcollection)
- [\Somnambulist\Components\ReadModels\Utils\GenerateRelationshipsToEagerLoad](#class-somnambulistcomponentsreadmodelsutilsgeneraterelationshipstoeagerload)
- [\Somnambulist\Components\ReadModels\Utils\StrConverter](#class-somnambulistcomponentsreadmodelsutilsstrconverter)

<hr /><a id="class-somnambulistcomponentsreadmodelsmodelexporter"></a>
### Class: \Somnambulist\Components\ReadModels\ModelExporter

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>[\Somnambulist\Components\ReadModels\Model](#class-somnambulistcomponentsreadmodelsmodel-abstract)</em> <strong>$model</strong>, <em>array</em> <strong>$attributes=array()</strong>, <em>array</em> <strong>$relationships=array()</strong>)</strong> : <em>void</em> |
| public | <strong>attributes(</strong><em>mixed</em> <strong>$attributes</strong>)</strong> : <em>[\Somnambulist\Components\ReadModels\ModelExporter](#class-somnambulistcomponentsreadmodelsmodelexporter)</em><br /><em>Export only the specified attributes; if empty will export all attributes</em> |
| public | <strong>include(</strong><em>mixed</em> <strong>$relationship</strong>)</strong> : <em>[\Somnambulist\Components\ReadModels\ModelExporter](#class-somnambulistcomponentsreadmodelsmodelexporter)</em><br /><em>Export with the specified relationships; if empty will NOT export any relationships</em> |
| public | <strong>toArray()</strong> : <em>void</em> |
| public | <strong>toJson(</strong><em>\int</em> <strong>$options</strong>)</strong> : <em>void</em> |

*This class implements \Somnambulist\Components\Collection\Contracts\Jsonable*

<hr /><a id="class-somnambulistcomponentsreadmodelspaginatoradapter"></a>
### Class: \Somnambulist\Components\ReadModels\PaginatorAdapter

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>[\Somnambulist\Components\ReadModels\Model](#class-somnambulistcomponentsreadmodelsmodel-abstract)Builder</em> <strong>$queryBuilder</strong>)</strong> : <em>void</em> |
| public | <strong>getNbResults()</strong> : <em>mixed</em> |
| public | <strong>getSlice(</strong><em>mixed</em> <strong>$offset</strong>, <em>mixed</em> <strong>$length</strong>)</strong> : <em>mixed</em> |

*This class implements \Pagerfanta\Adapter\AdapterInterface*

<hr /><a id="class-somnambulistcomponentsreadmodelsmodel-abstract"></a>
### Class: \Somnambulist\Components\ReadModels\Model (abstract)

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>array</em> <strong>$attributes=array()</strong>)</strong> : <em>void</em> |
| public | <strong>__toString()</strong> : <em>void</em> |
| public | <strong>export()</strong> : <em>void</em> |
| public static | <strong>find(</strong><em>string</em> <strong>$id</strong>)</strong> : <em>[\Somnambulist\Components\ReadModels\Model](#class-somnambulistcomponentsreadmodelsmodel-abstract)/null</em> |
| public static | <strong>findOrFail(</strong><em>string</em> <strong>$id</strong>)</strong> : <em>[\Somnambulist\Components\ReadModels\Model](#class-somnambulistcomponentsreadmodelsmodel-abstract)</em> |
| public | <strong>getAttribute(</strong><em>\string</em> <strong>$name</strong>)</strong> : <em>mixed/null</em><br /><em>Get the requested attribute or relationship If a mutator is defined (getXxxxAttribute method), the attribute will be passed through that first. If the attribute does not exist a virtual accessor will be checked and return if there is one. Finally, if the relationship exists and has not been loaded, it will be at this point.</em> |
| public | <strong>getAttributes()</strong> : <em>mixed</em> |
| public | <strong>getCollection(</strong><em>array</em> <strong>$models=array()</strong>)</strong> : <em>mixed</em> |
| public | <strong>getExternalPrimaryKey()</strong> : <em>mixed/null</em><br /><em>Could return an object e.g. Uuid or string, depending on casting</em> |
| public | <strong>getOwningKey()</strong> : <em>mixed</em><br /><em>Gets the owning side of the relationships key name</em> |
| public | <strong>getPrimaryKey()</strong> : <em>mixed/null</em><br /><em>Could return an object e.g. Uuid, Identity, depending on casting</em> |
| public | <strong>getRelationship(</strong><em>\string</em> <strong>$method</strong>)</strong> : <em>[\Somnambulist\Components\ReadModels\Relationships\AbstractRelationship](#class-somnambulistcomponentsreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Returns the relationship definition defined by the method name E.g. a User model hasMany Roles, the method would be "roles()".</em> |
| public static | <strong>include(</strong><em>mixed</em> <strong>$relations</strong>)</strong> : <em>[\Somnambulist\Components\ReadModels\Model](#class-somnambulistcomponentsreadmodelsmodel-abstract)Builder</em><br /><em>Eager load the specified relationships on this model Allows dot notation to load `related.related` objects.</em> |
| public | <strong>jsonSerialize()</strong> : <em>void</em> |
| public | <strong>meta()</strong> : <em>void</em> |
| public | <strong>new(</strong><em>array</em> <strong>$attributes=array()</strong>)</strong> : <em>void</em> |
| public | <strong>newQuery()</strong> : <em>void</em> |
| public static | <strong>query()</strong> : <em>[\Somnambulist\Components\ReadModels\Model](#class-somnambulistcomponentsreadmodelsmodel-abstract)Builder</em><br /><em>Starts a new query builder process without any constraints</em> |
| public | <strong>setRelationshipValue(</strong><em>\string</em> <strong>$method</strong>, <em>\object</em> <strong>$related</strong>)</strong> : <em>void</em> |
| public | <strong>toArray()</strong> : <em>void</em> |
| public | <strong>toJson(</strong><em>\int</em> <strong>$options</strong>)</strong> : <em>void</em> |
| protected | <strong>belongsTo(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$foreignKey=null</strong>, <em>\string</em> <strong>$ownerKey=null</strong>, <em>\string</em> <strong>$relation=null</strong>, <em>\bool</em> <strong>$nullOnNotFound=true</strong>)</strong> : <em>\Somnambulist\Components\ReadModels\Relationships\BelongsTo</em><br /><em>Define an inverse one-to-one or many relationship The table in this case will be the owning side of the relationship i.e. the originator of the foreign key on the specified class. For example: a User has many Addresses, the address table has a key: user_id linking the address to the user. This relationship finds the user from the users table where the users.id = user_addresses.user_id. This will only associate a single model as the inverse side, nor will it update the owner with this models association.</em> |
| protected | <strong>belongsToMany(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$table</strong>, <em>\string</em> <strong>$sourceColumnName=null</strong>, <em>\string</em> <strong>$targetColumnName=null</strong>, <em>\string</em> <strong>$sourceKey=null</strong>, <em>\string</em> <strong>$targetKey=null</strong>)</strong> : <em>\Somnambulist\Components\ReadModels\Relationships\BelongsToMany</em><br /><em>Define a new many to many relationship The table is the joining table between the source and the target. The source is the object at the left hand side of the relationship, the target is on the right. For example: User -> Roles through a user_roles table, with user_id, role_id as columns. The relationship would be defined as a User has Roles so the source is user_id and the target is role_id. The table name must be provided and will not be guessed.</em> |
| protected | <strong>hasMany(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$foreignKey=null</strong>, <em>\string</em> <strong>$parentKey=null</strong>, <em>\string</em> <strong>$indexBy=null</strong>)</strong> : <em>\Somnambulist\Components\ReadModels\Relationships\HasOneToMany</em><br /><em>Define a one to many relationship Here, the parent has many children, so a User can have many addresses. The foreign key is the name of the parents key in the child's table. local key is the child's primary key. indexBy allows a column on the child to be used as the key in the returned collection. Note: if this is specified, then there can be only a single instance of that key returned. This would usually be used on related objects with a type where, the parent can only have one of each type e.g.: a contact has a "type" field for: home, office, cell etc.</em> |
| protected | <strong>hasOne(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$foreignKey=null</strong>, <em>\string</em> <strong>$parentKey=null</strong>, <em>\bool</em> <strong>$nullOnNotFound=true</strong>)</strong> : <em>\Somnambulist\Components\ReadModels\Relationships\HasOne</em><br /><em>Defines a one to one relationship Here the parent has only one child and the child only has that parent. If multiple records end up being stored, then only the first will be loaded.</em> |

*This class extends \Somnambulist\Components\AttributeModel\AbstractModel*

*This class implements \Somnambulist\Components\Collection\Contracts\Arrayable, \Somnambulist\Components\Collection\Contracts\Jsonable, \JsonSerializable, \Stringable*

<hr /><a id="class-somnambulistcomponentsreadmodelsmodelmetadata"></a>
### Class: \Somnambulist\Components\ReadModels\ModelMetadata

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>[\Somnambulist\Components\ReadModels\Model](#class-somnambulistcomponentsreadmodelsmodel-abstract)</em> <strong>$model</strong>, <em>\string</em> <strong>$table</strong>, <em>\string</em> <strong>$primaryKey=`'id'`</strong>, <em>\string</em> <strong>$alias=null</strong>, <em>\string</em> <strong>$externalKey=null</strong>, <em>\string</em> <strong>$foreignKey=null</strong>)</strong> : <em>void</em> |
| public | <strong>externalKeyName()</strong> : <em>void</em> |
| public | <strong>foreignKey()</strong> : <em>string</em><br /><em>Creates a foreign key name from the current class name and primary key name This is used in relationships if a specific foreign key column name is not defined on the relationship.</em> |
| public | <strong>prefixAlias(</strong><em>\string</em> <strong>$column</strong>)</strong> : <em>void</em> |
| public | <strong>primaryKeyName()</strong> : <em>void</em> |
| public | <strong>primaryKeyNameWithAlias()</strong> : <em>void</em> |
| public | <strong>removeAlias(</strong><em>\string</em> <strong>$key</strong>)</strong> : <em>void</em> |
| public | <strong>table()</strong> : <em>void</em> |
| public | <strong>tableAlias()</strong> : <em>void</em> |

<hr /><a id="class-somnambulistcomponentsreadmodelsconnectionmanager"></a>
### Class: \Somnambulist\Components\ReadModels\ConnectionManager

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>array</em> <strong>$connections</strong>)</strong> : <em>void</em> |
| public | <strong>add(</strong><em>\Doctrine\DBAL\Connection</em> <strong>$connection</strong>, <em>\string</em> <strong>$model=`'default'`</strong>)</strong> : <em>void</em><br /><em>Set the DBAL Connection to use by default or for a specific model The model class name should be used and then that connection will be used with all instances of that model. A default connection should still be provided as a fallback.</em> |
| public | <strong>for(</strong><em>\string</em> <strong>$model=`'default'`</strong>)</strong> : <em>\Doctrine\DBAL\Connection</em><br /><em>Get the model or default connection</em> |
| public | <strong>forAll(</strong><em>array</em> <strong>$connections</strong>)</strong> : <em>void</em> |

<hr /><a id="interface-somnambulistcomponentsreadmodelscontractsqueryable"></a>
### Interface: \Somnambulist\Components\ReadModels\Contracts\Queryable

> Tags an object as supporting or allowing pass through into the Builder object. This allows for type completion and hints where they might not otherwise exist.

| Visibility | Function |
|:-----------|:---------|

<hr /><a id="class-somnambulistcomponentsreadmodelseventsubscribersidentitymapclearermessengersubscriber"></a>
### Class: \Somnambulist\Components\ReadModels\EventSubscribers\IdentityMapClearerMessengerSubscriber

> Clears the read-model identity map when being used in Messenger to avoid stale data after a command has been executed. Based on DoctrineBridge::DoctrineClearEntityManagerWorkerSubscriber

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\Somnambulist\Components\ReadModels\Manager</em> <strong>$manager</strong>)</strong> : <em>void</em> |
| public static | <strong>getSubscribedEvents()</strong> : <em>mixed</em> |
| public | <strong>onWorkerMessageFailed()</strong> : <em>void</em> |
| public | <strong>onWorkerMessageHandled()</strong> : <em>void</em> |

*This class implements \Symfony\Component\EventDispatcher\EventSubscriberInterface*

<hr /><a id="class-somnambulistcomponentsreadmodelseventsubscribersidentitymapclearersubscriber"></a>
### Class: \Somnambulist\Components\ReadModels\EventSubscribers\IdentityMapClearerSubscriber

> Kernel subscriber that clears the identity map onRequest start, exception or terminate ensuring that the identity map is fresh for each request. When running under php-fpm this should not be needed; however if you use a PHP application server, that does not terminate, then the identity map will not be cleared between request (e.g. PHP-PM).

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\Somnambulist\Components\ReadModels\Manager</em> <strong>$manager</strong>)</strong> : <em>void</em> |
| public static | <strong>getSubscribedEvents()</strong> : <em>mixed</em> |
| public | <strong>onException(</strong><em>\Symfony\Component\HttpKernel\Event\KernelEvent</em> <strong>$event</strong>)</strong> : <em>void</em> |
| public | <strong>onRequest(</strong><em>\Symfony\Component\HttpKernel\Event\KernelEvent</em> <strong>$event</strong>)</strong> : <em>void</em> |
| public | <strong>onTerminate(</strong><em>\Symfony\Component\HttpKernel\Event\KernelEvent</em> <strong>$event</strong>)</strong> : <em>void</em> |

*This class implements \Symfony\Component\EventDispatcher\EventSubscriberInterface*

<hr /><a id="class-somnambulistcomponentsreadmodelsexceptionsentitynotfoundexception"></a>
### Class: \Somnambulist\Components\ReadModels\Exceptions\EntityNotFoundException

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>noMatchingRecordFor(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$key</strong>, <em>mixed</em> <strong>$id</strong>)</strong> : <em>void</em> |

*This class extends \Exception*

*This class implements \Throwable, \Stringable*

<hr /><a id="class-somnambulistcomponentsreadmodelsexceptionsnoresultsexception"></a>
### Class: \Somnambulist\Components\ReadModels\Exceptions\NoResultsException

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\string</em> <strong>$class</strong>, <em>\Doctrine\DBAL\Query\QueryBuilder</em> <strong>$queryBuilder</strong>)</strong> : <em>void</em> |
| public | <strong>getQuery()</strong> : <em>mixed</em> |
| public static | <strong>noResultsForQuery(</strong><em>\string</em> <strong>$class</strong>, <em>\Doctrine\DBAL\Query\QueryBuilder</em> <strong>$queryBuilder</strong>)</strong> : <em>void</em> |

*This class extends \Exception*

*This class implements \Throwable, \Stringable*

<hr /><a id="class-somnambulistcomponentsreadmodelsexceptionsconnectionmanagerexception"></a>
### Class: \Somnambulist\Components\ReadModels\Exceptions\ConnectionManagerException

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>missingConnectionFor(</strong><em>\string</em> <strong>$model</strong>)</strong> : <em>void</em> |

*This class extends \Exception*

*This class implements \Throwable, \Stringable*

<hr /><a id="class-somnambulistcomponentsreadmodelsphpunitphpunitlistener"></a>
### Class: \Somnambulist\Components\ReadModels\PHPUnit\PHPUnitListener

> Ensures that the identity map is cleared before and after every test case is run. If this is not used, then the identity map will persist across tests, giving false results. Enable it by adding the following to your phpunit.xml file: <code> <extensions> <extension class="Somnambulist\Components\ReadModels\PHPUnit\PHPUnitListener" /> </extensions> </code>

| Visibility | Function |
|:-----------|:---------|
| public | <strong>executeAfterTest(</strong><em>\string</em> <strong>$test</strong>, <em>\float</em> <strong>$time</strong>)</strong> : <em>void</em> |
| public | <strong>executeBeforeTest(</strong><em>\string</em> <strong>$test</strong>)</strong> : <em>void</em> |

*This class implements \PHPUnit\Runner\BeforeTestHook, \PHPUnit\Runner\AfterTestHook, \PHPUnit\Runner\Hook, \PHPUnit\Runner\TestHook*

<hr /><a id="class-somnambulistcomponentsreadmodelsrelationshipsabstractrelationship-abstract"></a>
### Class: \Somnambulist\Components\ReadModels\Relationships\AbstractRelationship (abstract)

> Represents a relationship between models. Relationships can be queried separately. Supports proxying calls to the Builder class

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__call(</strong><em>mixed</em> <strong>$name</strong>, <em>mixed</em> <strong>$arguments</strong>)</strong> : <em>void</em> |
| public | <strong>__construct(</strong><em>[\Somnambulist\Components\ReadModels\Model](#class-somnambulistcomponentsreadmodelsmodel-abstract)Builder</em> <strong>$builder</strong>, <em>[\Somnambulist\Components\ReadModels\Model](#class-somnambulistcomponentsreadmodelsmodel-abstract)</em> <strong>$parent</strong>)</strong> : <em>void</em> |
| public | <strong>addConstraintCallbackToQuery(</strong><em>\callable</em> <strong>$constraint</strong>)</strong> : <em>[\Somnambulist\Components\ReadModels\Relationships\AbstractRelationship](#class-somnambulistcomponentsreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Allows a callable to modify the current query before the results are fetched</em> |
| public | <strong>abstract addConstraints(</strong><em>\Somnambulist\Components\Collection\Contracts\Collection</em> <strong>$models</strong>)</strong> : <em>[\Somnambulist\Components\ReadModels\Relationships\AbstractRelationship](#class-somnambulistcomponentsreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Add the constraints required for loading a set of results</em> |
| public | <strong>abstract addRelationshipResultsToModels(</strong><em>\Somnambulist\Components\Collection\Contracts\Collection</em> <strong>$models</strong>, <em>\string</em> <strong>$relationship</strong>)</strong> : <em>[\Somnambulist\Components\ReadModels\Relationships\AbstractRelationship](#class-somnambulistcomponentsreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Executes and maps the loaded data to the collection of Models The related models will be inserted in the models relationships array at the key specified by $relationship.</em> |
| public | <strong>fetch()</strong> : <em>mixed</em> |
| public | <strong>getParent()</strong> : <em>mixed</em> |
| public | <strong>getQuery()</strong> : <em>mixed</em> |
| public | <strong>getRelated()</strong> : <em>mixed</em> |

*This class implements [\Somnambulist\Components\ReadModels\Contracts\Queryable](#interface-somnambulistcomponentsreadmodelscontractsqueryable)*

<hr /><a id="class-somnambulistcomponentsreadmodelsrelationshipshasoneormany-abstract"></a>
### Class: \Somnambulist\Components\ReadModels\Relationships\HasOneOrMany (abstract)

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>[\Somnambulist\Components\ReadModels\Model](#class-somnambulistcomponentsreadmodelsmodel-abstract)Builder</em> <strong>$builder</strong>, <em>[\Somnambulist\Components\ReadModels\Model](#class-somnambulistcomponentsreadmodelsmodel-abstract)</em> <strong>$parent</strong>, <em>\string</em> <strong>$foreignKey</strong>, <em>\string</em> <strong>$localKey</strong>)</strong> : <em>void</em> |
| public | <strong>addConstraints(</strong><em>\Somnambulist\Components\Collection\Contracts\Collection</em> <strong>$models</strong>)</strong> : <em>void</em> |

*This class extends [\Somnambulist\Components\ReadModels\Relationships\AbstractRelationship](#class-somnambulistcomponentsreadmodelsrelationshipsabstractrelationship-abstract)*

*This class implements [\Somnambulist\Components\ReadModels\Contracts\Queryable](#interface-somnambulistcomponentsreadmodelscontractsqueryable)*

<hr /><a id="class-somnambulistcomponentsreadmodelstypecastersdoctrinetypecaster"></a>
### Class: \Somnambulist\Components\ReadModels\TypeCasters\DoctrineTypeCaster

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\Doctrine\DBAL\Connection</em> <strong>$connection</strong>)</strong> : <em>void</em> |
| public | <strong>cast(</strong><em>mixed</em> <strong>$attributes</strong>, <em>mixed</em> <strong>$attribute</strong>, <em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>supports(</strong><em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>types()</strong> : <em>void</em> |

*This class implements \Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface*

<hr /><a id="class-somnambulistcomponentsreadmodelsutilsclasshelpers"></a>
### Class: \Somnambulist\Components\ReadModels\Utils\ClassHelpers

> Assorted helpers, scoped to prevent global functions.

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>getCallingClass()</strong> : <em>string</em><br /><em>Returns the class that made a call to the current class</em> |
| public static | <strong>getCallingMethod()</strong> : <em>string</em><br /><em>Returns the function (method name) that called the function this is used in</em> |
| public static | <strong>getObjectShortClassName(</strong><em>\object</em> <strong>$object</strong>)</strong> : <em>string</em> |
| public static | <strong>set(</strong><em>\object</em> <strong>$object</strong>, <em>\string</em> <strong>$property</strong>, <em>\mixed</em> <strong>$value</strong>, <em>\mixed</em> <strong>$scope=null</strong>)</strong> : <em>object</em><br /><em>Set the property in object to value If scope is set to a parent class, private properties can be updated.</em> |
| public static | <strong>setPropertyArrayKey(</strong><em>\object</em> <strong>$object</strong>, <em>\string</em> <strong>$property</strong>, <em>\string</em> <strong>$key</strong>, <em>\mixed</em> <strong>$value</strong>, <em>\mixed</em> <strong>$scope=null</strong>)</strong> : <em>object</em><br /><em>Set an array key in the object property to value If scope is set to a parent class, private properties can be updated.</em> |

<hr /><a id="class-somnambulistcomponentsreadmodelsutilsfiltergeneratedkeysfromcollection"></a>
### Class: \Somnambulist\Components\ReadModels\Utils\FilterGeneratedKeysFromCollection

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__invoke(</strong><em>array/\Somnambulist\Components\ReadModels\Utils\Collection</em> <strong>$attributes</strong>)</strong> : <em>array</em><br /><em>Filters out library generated keys from the set of attributes</em> |

<hr /><a id="class-somnambulistcomponentsreadmodelsutilsgeneraterelationshipstoeagerload"></a>
### Class: \Somnambulist\Components\ReadModels\Utils\GenerateRelationshipsToEagerLoad

> Encapsulates the logic for generating eager loaded relationships. Based on the eager loading strategy deployed in Laravel Eloquent.

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__invoke(</strong><em>array</em> <strong>$toEagerLoad=array()</strong>, <em>mixed</em> <strong>$relations</strong>)</strong> : <em>array</em><br /><em>Set the relationships that should be eager loaded</em> |

<hr /><a id="class-somnambulistcomponentsreadmodelsutilsstrconverter"></a>
### Class: \Somnambulist\Components\ReadModels\Utils\StrConverter

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>toResource(</strong><em>\string</em> <strong>$string</strong>)</strong> : <em>bool/\Somnambulist\Components\ReadModels\Utils\resource</em><br /><em>Converts a string to a stream resource by passing it through a memory file</em> |

