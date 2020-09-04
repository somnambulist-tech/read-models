## Table of contents

- [\Somnambulist\ReadModels\ModelExporter](#class-somnambulistreadmodelsmodelexporter)
- [\Somnambulist\ReadModels\PaginatorAdapter](#class-somnambulistreadmodelspaginatoradapter)
- [\Somnambulist\ReadModels\Model (abstract)](#class-somnambulistreadmodelsmodel-abstract)
- [\Somnambulist\ReadModels\AttributeCaster](#class-somnambulistreadmodelsattributecaster)
- [\Somnambulist\ReadModels\ModelMetadata](#class-somnambulistreadmodelsmodelmetadata)
- [\Somnambulist\ReadModels\ConnectionManager](#class-somnambulistreadmodelsconnectionmanager)
- [\Somnambulist\ReadModels\Contracts\AttributeCaster (interface)](#interface-somnambulistreadmodelscontractsattributecaster)
- [\Somnambulist\ReadModels\Contracts\Queryable (interface)](#interface-somnambulistreadmodelscontractsqueryable)
- [\Somnambulist\ReadModels\EventSubscribers\IdentityMapClearerMessengerSubscriber](#class-somnambulistreadmodelseventsubscribersidentitymapclearermessengersubscriber)
- [\Somnambulist\ReadModels\EventSubscribers\IdentityMapClearerSubscriber](#class-somnambulistreadmodelseventsubscribersidentitymapclearersubscriber)
- [\Somnambulist\ReadModels\Exceptions\EntityNotFoundException](#class-somnambulistreadmodelsexceptionsentitynotfoundexception)
- [\Somnambulist\ReadModels\Exceptions\AttributeCasterException](#class-somnambulistreadmodelsexceptionsattributecasterexception)
- [\Somnambulist\ReadModels\Exceptions\JsonEncodingException](#class-somnambulistreadmodelsexceptionsjsonencodingexception)
- [\Somnambulist\ReadModels\Exceptions\NoResultsException](#class-somnambulistreadmodelsexceptionsnoresultsexception)
- [\Somnambulist\ReadModels\Exceptions\ConnectionManagerException](#class-somnambulistreadmodelsexceptionsconnectionmanagerexception)
- [\Somnambulist\ReadModels\PHPUnit\PHPUnitListener](#class-somnambulistreadmodelsphpunitphpunitlistener)
- [\Somnambulist\ReadModels\Relationships\AbstractRelationship (abstract)](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)
- [\Somnambulist\ReadModels\Relationships\HasOneOrMany (abstract)](#class-somnambulistreadmodelsrelationshipshasoneormany-abstract)
- [\Somnambulist\ReadModels\TypeCasters\AreaCaster](#class-somnambulistreadmodelstypecastersareacaster)
- [\Somnambulist\ReadModels\TypeCasters\MoneyCaster](#class-somnambulistreadmodelstypecastersmoneycaster)
- [\Somnambulist\ReadModels\TypeCasters\DistanceCaster](#class-somnambulistreadmodelstypecastersdistancecaster)
- [\Somnambulist\ReadModels\TypeCasters\ExternalIdentityCaster](#class-somnambulistreadmodelstypecastersexternalidentitycaster)
- [\Somnambulist\ReadModels\TypeCasters\DoctrineTypeCaster](#class-somnambulistreadmodelstypecastersdoctrinetypecaster)
- [\Somnambulist\ReadModels\TypeCasters\CoordinateCaster](#class-somnambulistreadmodelstypecasterscoordinatecaster)
- [\Somnambulist\ReadModels\Utils\ClassHelpers](#class-somnambulistreadmodelsutilsclasshelpers)
- [\Somnambulist\ReadModels\Utils\FilterGeneratedKeysFromCollection](#class-somnambulistreadmodelsutilsfiltergeneratedkeysfromcollection)
- [\Somnambulist\ReadModels\Utils\GenerateRelationshipsToEagerLoad](#class-somnambulistreadmodelsutilsgeneraterelationshipstoeagerload)
- [\Somnambulist\ReadModels\Utils\ProxyTo](#class-somnambulistreadmodelsutilsproxyto)
- [\Somnambulist\ReadModels\Utils\StrConverter](#class-somnambulistreadmodelsutilsstrconverter)

<hr />

### Class: \Somnambulist\ReadModels\ModelExporter

> Class ModelExporter

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> <strong>$model</strong>, <em>array</em> <strong>$attributes=array()</strong>, <em>array</em> <strong>$relationships=array()</strong>)</strong> : <em>void</em> |
| public | <strong>attributes(</strong><em>mixed</em> <strong>$attributes</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelExporter](#class-somnambulistreadmodelsmodelexporter)</em><br /><em>Export only the specified attributes; if empty will export all attributes</em> |
| public | <strong>toArray()</strong> : <em>void</em> |
| public | <strong>toJson(</strong><em>\int</em> <strong>$options</strong>)</strong> : <em>void</em> |
| public | <strong>with(</strong><em>mixed</em> <strong>$relationship</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelExporter](#class-somnambulistreadmodelsmodelexporter)</em><br /><em>Export with the specified relationships; if empty will NOT export any relationships</em> |

*This class implements \Somnambulist\Collection\Contracts\Jsonable*

<hr />

### Class: \Somnambulist\ReadModels\PaginatorAdapter

> Class PaginatorAdapter

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)Builder</em> <strong>$queryBuilder</strong>)</strong> : <em>void</em> |
| public | <strong>getNbResults()</strong> : <em>mixed</em> |
| public | <strong>getSlice(</strong><em>mixed</em> <strong>$offset</strong>, <em>mixed</em> <strong>$length</strong>)</strong> : <em>mixed</em> |

*This class implements \Pagerfanta\Adapter\AdapterInterface*

<hr />

### Class: \Somnambulist\ReadModels\Model (abstract)

> Class Model

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>array</em> <strong>$attributes=array()</strong>)</strong> : <em>void</em> |
| public | <strong>__toString()</strong> : <em>void</em> |
| public | <strong>export()</strong> : <em>void</em> |
| public static | <strong>find(</strong><em>string</em> <strong>$id</strong>)</strong> : <em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)/null</em> |
| public static | <strong>findOrFail(</strong><em>string</em> <strong>$id</strong>)</strong> : <em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> |
| public | <strong>getAttribute(</strong><em>\string</em> <strong>$name</strong>)</strong> : <em>mixed/null</em><br /><em>Get the requested attribute or relationship If a mutator is defined (getXxxxAttribute method), the attribute will be passed through that first. If the attribute does not exist a virtual accessor will be checked and return if there is one. Finally, if the relationship exists and has not been loaded, it will be at this point.</em> |
| public | <strong>getAttributes()</strong> : <em>mixed</em> |
| public | <strong>getExternalPrimaryKey()</strong> : <em>mixed/null</em><br /><em>Could return an object e.g. Uuid or string, depending on casting</em> |
| public | <strong>getOwningKey()</strong> : <em>mixed</em><br /><em>Gets the owning side of the relationships key name</em> |
| public | <strong>getPrimaryKey()</strong> : <em>mixed</em> |
| public | <strong>getRelationship(</strong><em>\string</em> <strong>$method</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Returns the relationship definition defined by the method name E.g. a User model hasMany Roles, the method would be "roles()".</em> |
| public | <strong>jsonSerialize()</strong> : <em>void</em> |
| public | <strong>meta()</strong> : <em>void</em> |
| public | <strong>new(</strong><em>array</em> <strong>$attributes=array()</strong>)</strong> : <em>void</em> |
| public | <strong>newQuery()</strong> : <em>void</em> |
| public static | <strong>query()</strong> : <em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)Builder</em><br /><em>Starts a new query builder process without any constraints</em> |
| public | <strong>toArray()</strong> : <em>void</em> |
| public | <strong>toJson(</strong><em>\int</em> <strong>$options</strong>)</strong> : <em>void</em> |
| public static | <strong>with(</strong><em>mixed</em> <strong>$relations</strong>)</strong> : <em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)Builder</em><br /><em>Eager load the specified relationships on this model Allows dot notation to load related.related objects.</em> |
| protected | <strong>belongsTo(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$foreignKey=null</strong>, <em>\string</em> <strong>$ownerKey=null</strong>, <em>\string</em> <strong>$relation=null</strong>, <em>\bool</em> <strong>$nullOnNotFound=true</strong>)</strong> : <em>\Somnambulist\ReadModels\Relationships\BelongsTo</em><br /><em>Define an inverse one-to-one or many relationship The table in this case will be the owning side of the relationship i.e. the originator of the foreign key on the specified class. For example: a User has many Addresses, the address table has a key: user_id linking the address to the user. This relationship finds the user from the users table where the users.id = user_addresses.user_id. This will only associate a single model as the inverse side, nor will it update the owner with this models association.</em> |
| protected | <strong>belongsToMany(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$table</strong>, <em>\string</em> <strong>$sourceColumnName=null</strong>, <em>\string</em> <strong>$targetColumnName=null</strong>, <em>\string</em> <strong>$sourceKey=null</strong>, <em>\string</em> <strong>$targetKey=null</strong>)</strong> : <em>\Somnambulist\ReadModels\Relationships\BelongsToMany</em><br /><em>Define a new many to many relationship The table is the joining table between the source and the target. The source is the object at the left hand side of the relationship, the target is on the right. For example: User -> Roles through a user_roles table, with user_id, role_id as columns. The relationship would be defined as a User has Roles so the source is user_id and the target is role_id. The table name must be provided and will not be guessed.</em> |
| protected | <strong>hasMany(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$foreignKey=null</strong>, <em>\string</em> <strong>$parentKey=null</strong>, <em>\string</em> <strong>$indexBy=null</strong>)</strong> : <em>\Somnambulist\ReadModels\Relationships\HasOneToMany</em><br /><em>Define a one to many relationship Here, the parent has many children, so a User can have many addresses. The foreign key is the name of the parents key in the child's table. local key is the child's primary key. indexBy allows a column on the child to be used as the key in the returned collection. Note: if this is specified, then there can be only a single instance of that key returned. This would usually be used on related objects with a type where, the parent can only have one of each type e.g.: a contact has a "type" field for: home, office, cell etc.</em> |
| protected | <strong>hasOne(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$foreignKey=null</strong>, <em>\string</em> <strong>$parentKey=null</strong>, <em>\bool</em> <strong>$nullOnNotFound=true</strong>)</strong> : <em>\Somnambulist\ReadModels\Relationships\HasOne</em><br /><em>Defines a one to one relationship Here the parent has only one child and the child only has that parent. If multiple records end up being stored, then only the first will be loaded.</em> |

*This class extends \Somnambulist\ReadModels\AbstractModel*

*This class implements \Somnambulist\Collection\Contracts\Arrayable, \Somnambulist\Collection\Contracts\Jsonable, \JsonSerializable*

<hr />

### Class: \Somnambulist\ReadModels\AttributeCaster

> Class AttributeCaster

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\iterable</em> <strong>$casters</strong>)</strong> : <em>void</em> |
| public | <strong>add(</strong><em>\Somnambulist\ReadModels\CasterInterface/[\Somnambulist\ReadModels\Contracts\AttributeCaster](#interface-somnambulistreadmodelscontractsattributecaster)</em> <strong>$caster</strong>, <em>array/null/array</em> <strong>$types=null</strong>)</strong> : <em>void</em> |
| public | <strong>addAll(</strong><em>\iterable</em> <strong>$casters</strong>)</strong> : <em>void</em> |
| public | <strong>cast(</strong><em>array</em> <strong>$attributes</strong>, <em>array</em> <strong>$casts</strong>)</strong> : <em>void</em> |
| public | <strong>extend(</strong><em>\string</em> <strong>$type</strong>, <em>array</em> <strong>$to</strong>)</strong> : <em>void</em><br /><em>Extends the bound AttributeCaster for $type to all types in $to</em> |
| public | <strong>for(</strong><em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>has(</strong><em>\string</em> <strong>$type</strong>)</strong> : <em>bool</em> |

<hr />

### Class: \Somnambulist\ReadModels\ModelMetadata

> Class ModelMetadata

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> <strong>$model</strong>, <em>\string</em> <strong>$table</strong>, <em>\string</em> <strong>$primaryKey=`'id'`</strong>, <em>\string</em> <strong>$alias=null</strong>, <em>\string</em> <strong>$externalKey=null</strong>, <em>\string</em> <strong>$foreignKey=null</strong>)</strong> : <em>void</em> |
| public | <strong>externalKeyName()</strong> : <em>void</em> |
| public | <strong>foreignKey()</strong> : <em>string</em><br /><em>Creates a foreign key name from the current class name and primary key name This is used in relationships if a specific foreign key column name is not defined on the relationship.</em> |
| public | <strong>prefixAlias(</strong><em>\string</em> <strong>$column</strong>)</strong> : <em>void</em> |
| public | <strong>primaryKeyName()</strong> : <em>void</em> |
| public | <strong>primaryKeyNameWithAlias()</strong> : <em>void</em> |
| public | <strong>removeAlias(</strong><em>\string</em> <strong>$key</strong>)</strong> : <em>void</em> |
| public | <strong>table()</strong> : <em>void</em> |
| public | <strong>tableAlias()</strong> : <em>void</em> |

<hr />

### Class: \Somnambulist\ReadModels\ConnectionManager

> Class ConnectionManager

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>array</em> <strong>$connections</strong>)</strong> : <em>void</em> |
| public | <strong>add(</strong><em>\Doctrine\DBAL\Connection</em> <strong>$connection</strong>, <em>\string</em> <strong>$model=`'default'`</strong>)</strong> : <em>void</em><br /><em>Set the DBAL Connection to use by default or for a specific model The model class name should be used and then that connection will be used with all instances of that model. A default connection should still be provided as a fallback.</em> |
| public | <strong>for(</strong><em>\string</em> <strong>$model=`'default'`</strong>)</strong> : <em>\Doctrine\DBAL\Connection</em><br /><em>Get the model or default connection</em> |
| public | <strong>forAll(</strong><em>array</em> <strong>$connections</strong>)</strong> : <em>void</em> |

<hr />

### Interface: \Somnambulist\ReadModels\Contracts\AttributeCaster

> Interface AttributeCaster

| Visibility | Function |
|:-----------|:---------|
| public | <strong>cast(</strong><em>array</em> <strong>$attributes</strong>, <em>\string</em> <strong>$attribute</strong>, <em>\string</em> <strong>$type</strong>)</strong> : <em>void</em><br /><em>Cast attributes to a particular type / object resetting the attribute value</em> |
| public | <strong>supports(</strong><em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>types()</strong> : <em>array</em><br /><em>An array of the type names that this caster will respond to</em> |

<hr />

### Interface: \Somnambulist\ReadModels\Contracts\Queryable

> Interface Queryable Tags an object as supporting or allowing pass through into the Builder object. This allows for type completion and hints where they might not otherwise exist.

| Visibility | Function |
|:-----------|:---------|

<hr />

### Class: \Somnambulist\ReadModels\EventSubscribers\IdentityMapClearerMessengerSubscriber

> Class IdentityMapClearerMessengerSubscriber Clears the read-model identity map when being used in Messenger to avoid stale data after a command has been executed. Based on DoctrineBridge::DoctrineClearEntityManagerWorkerSubscriber

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\Somnambulist\ReadModels\Manager</em> <strong>$manager</strong>)</strong> : <em>void</em> |
| public static | <strong>getSubscribedEvents()</strong> : <em>mixed</em> |
| public | <strong>onWorkerMessageFailed()</strong> : <em>void</em> |
| public | <strong>onWorkerMessageHandled()</strong> : <em>void</em> |

*This class implements \Symfony\Component\EventDispatcher\EventSubscriberInterface*

<hr />

### Class: \Somnambulist\ReadModels\EventSubscribers\IdentityMapClearerSubscriber

> Class IdentityMapClearerSubscriber Kernel subscriber that clears the identity map onRequest start, exception or terminate ensuring that the identity map is fresh for each request. When running under php-fpm this should not be needed; however if you use a PHP application server, that does not terminate, then the identity map will not be cleared between request (e.g. PHP-PM).

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\Somnambulist\ReadModels\Manager</em> <strong>$manager</strong>)</strong> : <em>void</em> |
| public static | <strong>getSubscribedEvents()</strong> : <em>mixed</em> |
| public | <strong>onException(</strong><em>\Symfony\Component\HttpKernel\Event\KernelEvent</em> <strong>$event</strong>)</strong> : <em>void</em> |
| public | <strong>onRequest(</strong><em>\Symfony\Component\HttpKernel\Event\KernelEvent</em> <strong>$event</strong>)</strong> : <em>void</em> |
| public | <strong>onTerminate(</strong><em>\Symfony\Component\HttpKernel\Event\KernelEvent</em> <strong>$event</strong>)</strong> : <em>void</em> |

*This class implements \Symfony\Component\EventDispatcher\EventSubscriberInterface*

<hr />

### Class: \Somnambulist\ReadModels\Exceptions\EntityNotFoundException

> Class EntityNotFoundException

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>noMatchingRecordFor(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$key</strong>, <em>mixed</em> <strong>$id</strong>)</strong> : <em>void</em> |

*This class extends \Exception*

*This class implements \Throwable*

<hr />

### Class: \Somnambulist\ReadModels\Exceptions\AttributeCasterException

> Class AttributeCasterException

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>missingTypeFor(</strong><em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |

*This class extends \Exception*

*This class implements \Throwable*

<hr />

### Class: \Somnambulist\ReadModels\Exceptions\JsonEncodingException

> Class JsonEncodingException

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>failedToConvertModel(</strong><em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> <strong>$model</strong>, <em>\string</em> <strong>$error</strong>)</strong> : <em>void</em> |

*This class extends \RuntimeException*

*This class implements \Throwable*

<hr />

### Class: \Somnambulist\ReadModels\Exceptions\NoResultsException

> Class NoResultsException

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\string</em> <strong>$class</strong>, <em>\Doctrine\DBAL\Query\QueryBuilder</em> <strong>$queryBuilder</strong>)</strong> : <em>void</em> |
| public | <strong>getQuery()</strong> : <em>mixed</em> |
| public static | <strong>noResultsForQuery(</strong><em>\string</em> <strong>$class</strong>, <em>\Doctrine\DBAL\Query\QueryBuilder</em> <strong>$queryBuilder</strong>)</strong> : <em>void</em> |

*This class extends \Exception*

*This class implements \Throwable*

<hr />

### Class: \Somnambulist\ReadModels\Exceptions\ConnectionManagerException

> Class ConnectionManagerException

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>missingConnectionFor(</strong><em>\string</em> <strong>$model</strong>)</strong> : <em>void</em> |

*This class extends \Exception*

*This class implements \Throwable*

<hr />

### Class: \Somnambulist\ReadModels\PHPUnit\PHPUnitListener

> Class PHPUnitListener Ensures that the identity map is cleared before and after every test case is run. If this is not used, then the identity map will persist across tests, giving false results. Enable it by adding the following to your phpunit.xml file: <code> <extensions> <extension class="Somnambulist\ReadModels\PHPUnit\PHPUnitListener" /> </extensions> </code>

| Visibility | Function |
|:-----------|:---------|
| public | <strong>executeAfterTest(</strong><em>\string</em> <strong>$test</strong>, <em>\float</em> <strong>$time</strong>)</strong> : <em>void</em> |
| public | <strong>executeBeforeTest(</strong><em>\string</em> <strong>$test</strong>)</strong> : <em>void</em> |

*This class implements \PHPUnit\Runner\BeforeTestHook, \PHPUnit\Runner\AfterTestHook, \PHPUnit\Runner\Hook, \PHPUnit\Runner\TestHook*

<hr />

### Class: \Somnambulist\ReadModels\Relationships\AbstractRelationship (abstract)

> Class AbstractRelationship

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__call(</strong><em>mixed</em> <strong>$name</strong>, <em>mixed</em> <strong>$arguments</strong>)</strong> : <em>void</em> |
| public | <strong>__construct(</strong><em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)Builder</em> <strong>$builder</strong>, <em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> <strong>$parent</strong>)</strong> : <em>void</em> |
| public | <strong>addConstraintCallbackToQuery(</strong><em>\callable</em> <strong>$constraint</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Allows a callable to modify the current query before the results are fetched</em> |
| public | <strong>abstract addConstraints(</strong><em>\Somnambulist\ReadModels\Relationships\Collection/\Somnambulist\Collection\MutableCollection</em> <strong>$models</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Add the constraints required for loading a set of results</em> |
| public | <strong>abstract addRelationshipResultsToModels(</strong><em>\Somnambulist\ReadModels\Relationships\Collection/\Somnambulist\Collection\MutableCollection</em> <strong>$models</strong>, <em>\string</em> <strong>$relationship</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Executes and maps the loaded data to the collection of Models The related models will be inserted in the models relationships array at the key specified by $relationship.</em> |
| public | <strong>fetch()</strong> : <em>mixed</em> |
| public | <strong>getParent()</strong> : <em>mixed</em> |
| public | <strong>getQuery()</strong> : <em>mixed</em> |
| public | <strong>getRelated()</strong> : <em>mixed</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\Queryable](#interface-somnambulistreadmodelscontractsqueryable)*

<hr />

### Class: \Somnambulist\ReadModels\Relationships\HasOneOrMany (abstract)

> Class HasOneOrMany

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)Builder</em> <strong>$builder</strong>, <em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> <strong>$parent</strong>, <em>\string</em> <strong>$foreignKey</strong>, <em>\string</em> <strong>$localKey</strong>)</strong> : <em>void</em> |
| public | <strong>addConstraints(</strong><em>\Somnambulist\Collection\MutableCollection</em> <strong>$models</strong>)</strong> : <em>void</em> |

*This class extends [\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)*

*This class implements [\Somnambulist\ReadModels\Contracts\Queryable](#interface-somnambulistreadmodelscontractsqueryable)*

<hr />

### Class: \Somnambulist\ReadModels\TypeCasters\AreaCaster

> Class AreaCaster

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\string</em> <strong>$areaAttribute=`'area_value'`</strong>, <em>\string</em> <strong>$unitAttribute=`'area_unit'`</strong>, <em>\bool</em> <strong>$remove=true</strong>)</strong> : <em>void</em> |
| public | <strong>cast(</strong><em>mixed</em> <strong>$attributes</strong>, <em>\string</em> <strong>$attribute</strong>, <em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>supports(</strong><em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>types()</strong> : <em>void</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\AttributeCaster](#interface-somnambulistreadmodelscontractsattributecaster)*

<hr />

### Class: \Somnambulist\ReadModels\TypeCasters\MoneyCaster

> Class MoneyCaster

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\string</em> <strong>$amtAttribute=`'amount'`</strong>, <em>\string</em> <strong>$curAttribute=`'currency'`</strong>, <em>\bool</em> <strong>$remove=true</strong>)</strong> : <em>void</em> |
| public | <strong>cast(</strong><em>mixed</em> <strong>$attributes</strong>, <em>\string</em> <strong>$attribute</strong>, <em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>supports(</strong><em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>types()</strong> : <em>void</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\AttributeCaster](#interface-somnambulistreadmodelscontractsattributecaster)*

<hr />

### Class: \Somnambulist\ReadModels\TypeCasters\DistanceCaster

> Class DistanceCaster

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\string</em> <strong>$distAttribute=`'distance_value'`</strong>, <em>\string</em> <strong>$unitAttribute=`'distance_unit'`</strong>, <em>\bool</em> <strong>$remove=true</strong>)</strong> : <em>void</em> |
| public | <strong>cast(</strong><em>mixed</em> <strong>$attributes</strong>, <em>\string</em> <strong>$attribute</strong>, <em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>supports(</strong><em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>types()</strong> : <em>void</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\AttributeCaster](#interface-somnambulistreadmodelscontractsattributecaster)*

<hr />

### Class: \Somnambulist\ReadModels\TypeCasters\ExternalIdentityCaster

> Class ExternalIdentityCaster

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\string</em> <strong>$providerAttribute=`'provider'`</strong>, <em>\string</em> <strong>$identityAttribute=`'identity'`</strong>, <em>\bool</em> <strong>$remove=true</strong>, <em>array</em> <strong>$types=null</strong>)</strong> : <em>void</em> |
| public | <strong>cast(</strong><em>mixed</em> <strong>$attributes</strong>, <em>\string</em> <strong>$attribute</strong>, <em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>supports(</strong><em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>types()</strong> : <em>void</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\AttributeCaster](#interface-somnambulistreadmodelscontractsattributecaster)*

<hr />

### Class: \Somnambulist\ReadModels\TypeCasters\DoctrineTypeCaster

> Class DoctrineTypeCaster

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\Doctrine\DBAL\Connection</em> <strong>$connection</strong>)</strong> : <em>void</em> |
| public | <strong>cast(</strong><em>mixed</em> <strong>$attributes</strong>, <em>\string</em> <strong>$attribute</strong>, <em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>supports(</strong><em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>types()</strong> : <em>void</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\AttributeCaster](#interface-somnambulistreadmodelscontractsattributecaster)*

<hr />

### Class: \Somnambulist\ReadModels\TypeCasters\CoordinateCaster

> Class CoordinateCaster

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\string</em> <strong>$latAttribute=`'latitude'`</strong>, <em>\string</em> <strong>$lngAttribute=`'longitude'`</strong>, <em>\string</em> <strong>$sridAttribute=`'srid'`</strong>, <em>\bool</em> <strong>$remove=true</strong>)</strong> : <em>void</em> |
| public | <strong>cast(</strong><em>mixed</em> <strong>$attributes</strong>, <em>\string</em> <strong>$attribute</strong>, <em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>supports(</strong><em>\string</em> <strong>$type</strong>)</strong> : <em>void</em> |
| public | <strong>types()</strong> : <em>void</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\AttributeCaster](#interface-somnambulistreadmodelscontractsattributecaster)*

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

### Class: \Somnambulist\ReadModels\Utils\FilterGeneratedKeysFromCollection

> Class FilterGeneratedAttributesAndKeysFromCollection

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__invoke(</strong><em>array/\Somnambulist\ReadModels\Utils\Collection</em> <strong>$attributes</strong>)</strong> : <em>array</em><br /><em>Filters out library generated keys from the set of attributes</em> |

<hr />

### Class: \Somnambulist\ReadModels\Utils\GenerateRelationshipsToEagerLoad

> Class GenerateRelationshipsToEagerLoad Encapsulates the logic for generating eager loaded relationships. Based on the eager loading strategy deployed in Laravel Eloquent.

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__invoke(</strong><em>array</em> <strong>$toEagerLoad=array()</strong>, <em>mixed</em> <strong>$relations</strong>)</strong> : <em>array</em><br /><em>Set the relationships that should be eager loaded</em> |

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

