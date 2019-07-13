## Table of contents

- [\Somnambulist\ReadModels\ModelExporter](#class-somnambulistreadmodelsmodelexporter)
- [\Somnambulist\ReadModels\PaginatorAdapter](#class-somnambulistreadmodelspaginatoradapter)
- [\Somnambulist\ReadModels\Model (abstract)](#class-somnambulistreadmodelsmodel-abstract)
- [\Somnambulist\ReadModels\FilterGeneratedKeysFromCollection](#class-somnambulistreadmodelsfiltergeneratedkeysfromcollection)
- [\Somnambulist\ReadModels\GenerateRelationshipsToEagerLoad](#class-somnambulistreadmodelsgeneraterelationshipstoeagerload)
- [\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)
- [\Somnambulist\ReadModels\Contracts\CanExportToJSON (interface)](#interface-somnambulistreadmodelscontractscanexporttojson)
- [\Somnambulist\ReadModels\Contracts\AttributeCaster (interface)](#interface-somnambulistreadmodelscontractsattributecaster)
- [\Somnambulist\ReadModels\Contracts\Queryable (interface)](#interface-somnambulistreadmodelscontractsqueryable)
- [\Somnambulist\ReadModels\Contracts\EmbeddableFactory (interface)](#interface-somnambulistreadmodelscontractsembeddablefactory)
- [\Somnambulist\ReadModels\Exceptions\EntityNotFoundException](#class-somnambulistreadmodelsexceptionsentitynotfoundexception)
- [\Somnambulist\ReadModels\Hydrators\SimpleTypeCaster](#class-somnambulistreadmodelshydratorssimpletypecaster)
- [\Somnambulist\ReadModels\Hydrators\SimpleObjectFactory](#class-somnambulistreadmodelshydratorssimpleobjectfactory)
- [\Somnambulist\ReadModels\Hydrators\DoctrineTypeCaster](#class-somnambulistreadmodelshydratorsdoctrinetypecaster)
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
| public | <strong>__construct(</strong><em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> <strong>$model</strong>, <em>array</em> <strong>$attributes=array()</strong>, <em>array</em> <strong>$relationships=array()</strong>)</strong> : <em>void</em><br /><em>Constructor.</em> |
| public | <strong>attributes(</strong><em>mixed</em> <strong>$attributes</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelExporter](#class-somnambulistreadmodelsmodelexporter)</em><br /><em>Export only the specified attributes; if empty will export all attributes</em> |
| public | <strong>toArray()</strong> : <em>array</em><br /><em>Create an array from the model data; including any specified relationships</em> |
| public | <strong>toJson(</strong><em>int</em> <strong>$options</strong>)</strong> : <em>string</em> |
| public | <strong>with(</strong><em>mixed</em> <strong>$relationship</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelExporter](#class-somnambulistreadmodelsmodelexporter)</em><br /><em>Export with the specified relationships; if empty will NOT export any relationships</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\CanExportToJSON](#interface-somnambulistreadmodelscontractscanexporttojson)*

<hr />

### Class: \Somnambulist\ReadModels\PaginatorAdapter

> Class PaginatorAdapter

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em> <strong>$queryBuilder</strong>)</strong> : <em>void</em><br /><em>Constructor.</em> |
| public | <strong>getNbResults()</strong> : <em>mixed</em> |
| public | <strong>getSlice(</strong><em>mixed</em> <strong>$offset</strong>, <em>mixed</em> <strong>$length</strong>)</strong> : <em>mixed</em> |

*This class implements \Pagerfanta\Adapter\AdapterInterface*

<hr />

### Class: \Somnambulist\ReadModels\Model (abstract)

> Class Model

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__call(</strong><em>string</em> <strong>$method</strong>, <em>array</em> <strong>$parameters</strong>)</strong> : <em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> |
| public static | <strong>__callStatic(</strong><em>string</em> <strong>$method</strong>, <em>array</em> <strong>$parameters</strong>)</strong> : <em>mixed</em><br /><em>Handle dynamic static method calls into the method.</em> |
| public | <strong>__construct(</strong><em>array</em> <strong>$attributes=array()</strong>)</strong> : <em>void</em><br /><em>Constructor.</em> |
| public | <strong>__get(</strong><em>string</em> <strong>$name</strong>)</strong> : <em>mixed/\Somnambulist\ReadModels\AbstractRelationship/null</em><br /><em>Allows accessing the attributes and relationships as properties</em> |
| public | <strong>__isset(</strong><em>mixed</em> <strong>$name</strong>)</strong> : <em>void</em> |
| public | <strong>__set(</strong><em>mixed</em> <strong>$name</strong>, <em>mixed</em> <strong>$value</strong>)</strong> : <em>void</em> |
| public | <strong>__toString()</strong> : <em>void</em> |
| public | <strong>__unset(</strong><em>mixed</em> <strong>$name</strong>)</strong> : <em>void</em> |
| public static | <strong>bindAttributeCaster(</strong><em>[\Somnambulist\ReadModels\Contracts\AttributeCaster](#interface-somnambulistreadmodelscontractsattributecaster)</em> <strong>$hydrator</strong>)</strong> : <em>void</em><br /><em>Change the primary attribute hydrator to another implementation Affects all models; should not be changed once objects have been loaded.</em> |
| public static | <strong>bindConnection(</strong><em>\Doctrine\DBAL\Connection</em> <strong>$connection</strong>, <em>\string</em> <strong>$model=`'default'`</strong>)</strong> : <em>void</em><br /><em>Set the DBAL Connection to use by default or for a specific model The model class name should be used and then that connection will be used with all instances of that model. A default connection should still be provided as a fallback.</em> |
| public static | <strong>bindEmbeddableFactory(</strong><em>[\Somnambulist\ReadModels\Contracts\EmbeddableFactory](#interface-somnambulistreadmodelscontractsembeddablefactory)</em> <strong>$hydrator</strong>)</strong> : <em>void</em><br /><em>Change the embeddable objects hydrator to another implementation Affects all models; should not be changed once objects have been loaded.</em> |
| public static | <strong>connection(</strong><em>\string</em> <strong>$model=null</strong>)</strong> : <em>\Doctrine\DBAL\Connection</em><br /><em>Get a specified or the default connection</em> |
| public | <strong>export()</strong> : <em>void</em> |
| public | <strong>getAttribute(</strong><em>\string</em> <strong>$name</strong>)</strong> : <em>mixed/null</em><br /><em>Get the requested attribute or relationship If a mutator is defined (getXxxxAttribute method), the attribute will be passed through that first. If the relationship has not been loaded, it will be at this point.</em> |
| public | <strong>getAttributes()</strong> : <em>array</em><br /><em>Returns all attributes, excluding the internally allocated attributes</em> |
| public | <strong>getForeignKey()</strong> : <em>string</em><br /><em>Creates a foreign key name from the current class name and primary key name This is used in relationships if a specific foreign key column name is not defined on the relationship.</em> |
| public | <strong>getIdentityMap()</strong> : <em>mixed</em> |
| public | <strong>getOwningKey()</strong> : <em>string/null</em><br /><em>Gets the owning side of the relationships key name</em> |
| public | <strong>getPrimaryKey()</strong> : <em>mixed</em> |
| public | <strong>getPrimaryKeyName()</strong> : <em>mixed</em> |
| public | <strong>getPrimaryKeyWithTableAlias()</strong> : <em>mixed</em> |
| public | <strong>getRelationship(</strong><em>\string</em> <strong>$method</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Returns the relationship definition defined by the method name E.g. a User model hasMany Roles, the method would be "roles()".</em> |
| public | <strong>getTable()</strong> : <em>mixed</em> |
| public | <strong>getTableAlias()</strong> : <em>mixed</em> |
| public | <strong>jsonSerialize()</strong> : <em>void</em> |
| public | <strong>new(</strong><em>array</em> <strong>$attributes=array()</strong>)</strong> : <em>void</em> |
| public | <strong>newQuery()</strong> : <em>void</em> |
| public | <strong>prefixColumnWithTableAlias(</strong><em>\string</em> <strong>$column</strong>)</strong> : <em>void</em> |
| public static | <strong>query()</strong> : <em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em><br /><em>Starts a new query builder process without any constraints</em> |
| public | <strong>removeTableAliasFrom(</strong><em>\string</em> <strong>$key</strong>)</strong> : <em>void</em> |
| public | <strong>toArray()</strong> : <em>void</em> |
| public | <strong>toJson(</strong><em>\int</em> <strong>$options</strong>)</strong> : <em>void</em> |
| public static | <strong>with(</strong><em>mixed</em> <strong>$relations</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em><br /><em>Eager load the specified relationships on this model Allows dot notation to load related.related objects.</em> |
| protected | <strong>belongsTo(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$foreignKey=null</strong>, <em>\string</em> <strong>$ownerKey=null</strong>, <em>\string</em> <strong>$relation=null</strong>)</strong> : <em>\Somnambulist\ReadModels\Relationships\BelongsTo</em><br /><em>Define an inverse one-to-one or many relationship The table in this case will be the owning side of the relationship i.e. the originator of the foreign key on the specified class. For example: a User has many Addresses, the address table has a key: user_id linking the address to the user. This relationship finds the user from the users table where the users.id = user_addresses.user_id. This will only associate a single model as the inverse side, nor will it update the owner with this models association.</em> |
| protected | <strong>belongsToMany(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$table=null</strong>, <em>\string</em> <strong>$tableSourceKey=null</strong>, <em>\string</em> <strong>$tableTargetKey=null</strong>, <em>\string</em> <strong>$sourceKey=null</strong>, <em>\string</em> <strong>$targetKey=null</strong>)</strong> : <em>\Somnambulist\ReadModels\Relationships\BelongsToMany</em><br /><em>Define a new many to many relationship The table is the joining table between the source and the target. The source is the object at the left hand side of the relationship, the target is on the right. For example: User -> Roles through a user_roles table, with user_id, role_id as columns. The relationship would be defined as a User has Roles so the source is user_id and the target is role_id. If the table is not given, it will be guessed using the source table singularized and the target pluralized, separated by an underscore e.g.: users and roles would create: user_roles</em> |
| protected | <strong>hasMany(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$foreignKey=null</strong>, <em>\string</em> <strong>$localKey=null</strong>, <em>\string</em> <strong>$indexBy=null</strong>)</strong> : <em>\Somnambulist\ReadModels\Relationships\HasOneToMany</em><br /><em>Define a one to many relationship Here, the parent has many children, so a User can have many addresses. The foreign key is the name of the parents key in the child's table. local key is the child's primary key. indexBy allows a column on the child to be used as the key in the returned collection. Note: if this is specified, then there can be only a single instance of that key returned. This would usually be used on related objects with a type where, the parent can only have one of each type e.g.: a contact has a "type" field for: home, office, cell etc.</em> |
| protected | <strong>hasOne(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$foreignKey=null</strong>, <em>\string</em> <strong>$localKey=null</strong>)</strong> : <em>\Somnambulist\ReadModels\Relationships\HasOne</em><br /><em>Defines a one to one relationship Here the parent has only one child and the child only has that parent. If multiple records end up being stored, then only the first will be loaded.</em> |

*This class implements \Somnambulist\Collection\Contracts\Arrayable, \Somnambulist\Collection\Contracts\Jsonable, \JsonSerializable, [\Somnambulist\ReadModels\Contracts\Queryable](#interface-somnambulistreadmodelscontractsqueryable)*

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

### Class: \Somnambulist\ReadModels\ModelBuilder

> Class ModelBuilder

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__call(</strong><em>string</em> <strong>$name</strong>, <em>array</em> <strong>$arguments</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em><br /><em>Allow pass through to certain QueryBuilder methods but return this Builder</em> |
| public | <strong>__construct(</strong><em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> <strong>$model</strong>, <em>\Doctrine\DBAL\Query\QueryBuilder</em> <strong>$query</strong>)</strong> : <em>void</em><br /><em>Constructor.</em> |
| public | <strong>count()</strong> : <em>int</em><br /><em>Executes the current query, returning a count of total matched records count operates on a copy of the current query.</em> |
| public | <strong>expression()</strong> : <em>\Doctrine\DBAL\Query\Expression\ExpressionBuilder</em><br /><em>The DBAL Expression builder object for creating where clauses</em> |
| public | <strong>fetch()</strong> : <em>\Somnambulist\ReadModels\Collection</em><br /><em>Executes the current query, returning a Collection of results</em> |
| public | <strong>find(</strong><em>string</em> <strong>$id</strong>, <em>mixed</em> <strong>$columns</strong>)</strong> : <em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)/null</em><br /><em>Find the model by primary key, optionally returning just the specified columns</em> |
| public | <strong>findOrFail(</strong><em>string</em> <strong>$id</strong>, <em>mixed</em> <strong>$columns</strong>)</strong> : <em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em><br /><em>Find the model by the primary key, but raise an exception if not found</em> |
| public | <strong>getIdentityMap()</strong> : <em>mixed</em> |
| public | <strong>getModel()</strong> : <em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> |
| public | <strong>getQueryBuilder()</strong> : <em>\Doctrine\DBAL\Query\QueryBuilder</em><br /><em>Gets the underlying DBAL query builder Note: this provides total access to all bound data include query parts. Use with caution.</em> |
| public | <strong>groupBy(</strong><em>\string</em> <strong>$column</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em><br /><em>Group by a column in the select clause Note: if you add a group by, any non-aggregate selected column must also appear in the group by.</em> |
| public | <strong>hasSelectExpression(</strong><em>\string</em> <strong>$expression</strong>)</strong> : <em>bool</em><br /><em>Returns true if the expression has been bound to the select clause Search is performed using "contains" and could match similar strings. For example: a check for contains "user_id" would return true for any select clause that contains the string user_id (user.id AS user_id, related_user_id etc). For better results, be sure to check for a specific expression. Selects should be relatively unique, unless extremely complex.</em> |
| public | <strong>limit(</strong><em>\int</em> <strong>$limit</strong>)</strong> : <em>void</em> |
| public | <strong>newQuery()</strong> : <em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em><br /><em>Returns a new query builder instance for the set Model</em> |
| public | <strong>offset(</strong><em>\int</em> <strong>$offset</strong>)</strong> : <em>void</em> |
| public | <strong>orWhere(</strong><em>\string</em> <strong>$expression</strong>, <em>array</em> <strong>$values=array()</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em><br /><em>Add an arbitrarily complex OR expression to the query The same rules apply as for the AND version. Values must use named placeholders.</em> |
| public | <strong>orWhereBetween(</strong><em>\string</em> <strong>$column</strong>, <em>mixed</em> <strong>$start</strong>, <em>mixed</em> <strong>$end</strong>)</strong> : <em>void</em> |
| public | <strong>orWhereColumn(</strong><em>\string</em> <strong>$column</strong>, <em>\string</em> <strong>$operator</strong>, <em>mixed</em> <strong>$value</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em><br /><em>Add an or column to the where clause</em> |
| public | <strong>orWhereIn(</strong><em>\string</em> <strong>$column</strong>, <em>mixed</em> <strong>$values</strong>)</strong> : <em>void</em> |
| public | <strong>orWhereNotBetween(</strong><em>\string</em> <strong>$column</strong>, <em>mixed</em> <strong>$start</strong>, <em>mixed</em> <strong>$end</strong>)</strong> : <em>void</em> |
| public | <strong>orWhereNotIn(</strong><em>\string</em> <strong>$column</strong>, <em>mixed</em> <strong>$values</strong>)</strong> : <em>void</em> |
| public | <strong>orWhereNotNull(</strong><em>\string</em> <strong>$column</strong>)</strong> : <em>void</em> |
| public | <strong>orWhereNull(</strong><em>\string</em> <strong>$column</strong>)</strong> : <em>void</em> |
| public | <strong>orderBy(</strong><em>\string</em> <strong>$column</strong>, <em>\string</em> <strong>$dir=`'ASC'`</strong>)</strong> : <em>void</em> |
| public | <strong>paginate(</strong><em>\int</em> <strong>$page=1</strong>, <em>\int</em> <strong>$perPage=30</strong>)</strong> : <em>\Pagerfanta\Pagerfanta</em><br /><em>Returns a paginator that can be iterated with results Note: the paginator may not cope with all types of select and group by. You may need to scale back the types of queries you run.</em> |
| public | <strong>select(</strong><em>mixed</em> <strong>$columns</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em><br /><em>Select specific columns from the current model Use multiple arguments: ->select('id', 'name', 'created_at')...</em> |
| public | <strong>where(</strong><em>\string</em> <strong>$expression</strong>, <em>array</em> <strong>$values=array()</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em><br /><em>Add an arbitrarily complex AND expression to the query This method allows raw SQL, SELECT ... and basically anything you can put in a where. Values _must_ be passed as key -> value where the key is the NAMED placeholder. ? placeholders are not supported in this builder. If the parameter is already bound, it will be overwritten with the value in the values array.</em> |
| public | <strong>whereBetween(</strong><em>\string</em> <strong>$column</strong>, <em>mixed</em> <strong>$start</strong>, <em>mixed</em> <strong>$end</strong>, <em>\string</em> <strong>$andOr=`'and'`</strong>, <em>\bool</em> <strong>$not=false</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em><br /><em>Adds a <column> BETWEEN <start> AND <end> to the query Start and end can be any valid value supported by the DB for BETWEEN. e.g. dates, ints, floats If using a date on a datetime field, note that it is usually treated as midnight to midnight so may not include all results, in those instances either go 1 day higher or set the time to 23:59:59.</em> |
| public | <strong>whereColumn(</strong><em>\string</em> <strong>$column</strong>, <em>\string</em> <strong>$operator</strong>, <em>mixed</em> <strong>$value</strong>, <em>\string</em> <strong>$andOr=`'and'`</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em><br /><em>Add a WHERE <column> condition to the query Specifically works with the column. Operator can be any valid SQL operator that can accept a value including like, ilike.</em> |
| public | <strong>whereIn(</strong><em>\string</em> <strong>$column</strong>, <em>array/\Somnambulist\ReadModels\Arrayable</em> <strong>$values</strong>, <em>\string</em> <strong>$andOr=`'and'`</strong>, <em>\bool</em> <strong>$not=false</strong>)</strong> : <em>\Somnambulist\ReadModels\$this</em><br /><em>Create a WHERE column IN () clause with support for and or or and NOT IN ()</em> |
| public | <strong>whereNotBetween(</strong><em>\string</em> <strong>$column</strong>, <em>mixed</em> <strong>$start</strong>, <em>mixed</em> <strong>$end</strong>)</strong> : <em>void</em> |
| public | <strong>whereNotIn(</strong><em>\string</em> <strong>$column</strong>, <em>mixed</em> <strong>$values</strong>)</strong> : <em>void</em> |
| public | <strong>whereNotNull(</strong><em>\string</em> <strong>$column</strong>)</strong> : <em>void</em> |
| public | <strong>whereNull(</strong><em>\string</em> <strong>$column</strong>, <em>\string</em> <strong>$andOr=`'and'`</strong>, <em>\bool</em> <strong>$not=false</strong>)</strong> : <em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em> |
| public | <strong>wherePrimaryKey(</strong><em>mixed</em> <strong>$id</strong>)</strong> : <em>\Somnambulist\ReadModels\$this</em><br /><em>Add a where clause on the primary key to the query</em> |
| public | <strong>with(</strong><em>mixed</em> <strong>$relations</strong>)</strong> : <em>\Somnambulist\ReadModels\$this</em><br /><em>Set the relationships that should be eager loaded</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\Queryable](#interface-somnambulistreadmodelscontractsqueryable)*

<hr />

### Interface: \Somnambulist\ReadModels\Contracts\CanExportToJSON

> Interface CanExportToJSON

| Visibility | Function |
|:-----------|:---------|
| public | <strong>toJson(</strong><em>mixed</em> <strong>$options</strong>)</strong> : <em>void</em> |

<hr />

### Interface: \Somnambulist\ReadModels\Contracts\AttributeCaster

> Interface AttributeCaster

| Visibility | Function |
|:-----------|:---------|
| public | <strong>cast(</strong><em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> <strong>$model</strong>, <em>array</em> <strong>$attributes=array()</strong>, <em>array</em> <strong>$casts=array()</strong>)</strong> : <em>array</em><br /><em>Cast attributes to a type or simple object (or not) Return the modified attributes an array.</em> |

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

### Class: \Somnambulist\ReadModels\Exceptions\EntityNotFoundException

> Class EntityNotFoundException

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>noMatchingRecordFor(</strong><em>\string</em> <strong>$class</strong>, <em>\string</em> <strong>$key</strong>, <em>string/int</em> <strong>$id</strong>)</strong> : <em>[\Somnambulist\ReadModels\Exceptions\EntityNotFoundException](#class-somnambulistreadmodelsexceptionsentitynotfoundexception)</em> |

*This class extends \Exception*

*This class implements \Throwable*

<hr />

### Class: \Somnambulist\ReadModels\Hydrators\SimpleTypeCaster

> Class NullTypeCaster

| Visibility | Function |
|:-----------|:---------|
| public | <strong>cast(</strong><em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> <strong>$model</strong>, <em>array</em> <strong>$attributes=array()</strong>, <em>array</em> <strong>$casts=array()</strong>)</strong> : <em>array</em><br /><em>Only removes any table prefixes and performs no other casting</em> |

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
| public | <strong>cast(</strong><em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> <strong>$model</strong>, <em>array</em> <strong>$attributes=array()</strong>, <em>array</em> <strong>$casts=array()</strong>)</strong> : <em>array</em><br /><em>Cast attributes to a configured Doctrine type through the model connection Casts is an array of attribute name to a valid Doctrine type. The type must be registered to use it. Certain types require a resource and for these `resource:` should be prefixed on the attribute name. This will trigger a conversion of the stream to a resource stream. e.g. for Creof Postgres geo-spatial types. The modified attributes are returned to the caller. Note: Doctrine Types are case-sensitive.</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\AttributeCaster](#interface-somnambulistreadmodelscontractsattributecaster)*

<hr />

### Class: \Somnambulist\ReadModels\Relationships\AbstractRelationship (abstract)

> Class AbstractRelationship

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__call(</strong><em>string</em> <strong>$name</strong>, <em>array</em> <strong>$arguments</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)/\Somnambulist\ReadModels\Relationships\ModelBuilder/mixed</em><br /><em>Pass on calls to the builder instance, or return the result of the call</em> |
| public | <strong>__construct(</strong><em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em> <strong>$builder</strong>, <em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> <strong>$parent</strong>)</strong> : <em>void</em><br /><em>Constructor.</em> |
| public | <strong>addConstraintCallbackToQuery(</strong><em>\callable</em> <strong>$constraint</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Allows a callable to modify the current query before the results are fetched</em> |
| public | <strong>abstract addEagerLoadingConstraints(</strong><em>\Somnambulist\ReadModels\Relationships\Collection/\Somnambulist\Collection\MutableCollection</em> <strong>$models</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Add the constraints required for eager loading a set of results This should initiate a new query to avoid the initialiseRelationship query.</em> |
| public | <strong>addEagerLoadingRelationships(</strong><em>mixed</em> <strong>$relationships</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Adds additional models to eager load to the fetch</em> |
| public | <strong>abstract addEagerLoadingResults(</strong><em>\Somnambulist\ReadModels\Relationships\Collection/\Somnambulist\Collection\MutableCollection</em> <strong>$models</strong>, <em>\string</em> <strong>$relationship</strong>)</strong> : <em>[\Somnambulist\ReadModels\Relationships\AbstractRelationship](#class-somnambulistreadmodelsrelationshipsabstractrelationship-abstract)</em><br /><em>Executes and maps the eager loaded data to the collection of Models The related models will be inserted in the models relationships array at the key specified by $relationship.</em> |
| public | <strong>fetch()</strong> : <em>\Somnambulist\ReadModels\Relationships\Collection</em><br /><em>Executes the relationship, returning any results</em> |
| public | <strong>getParent()</strong> : <em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em><br /><em>Returns the parent (owner) of this relationship</em> |
| public | <strong>getQuery()</strong> : <em>mixed</em> |
| public | <strong>getRelated()</strong> : <em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em><br /><em>Returns the related object (the current subject) of this relationship</em> |
| public | <strong>hasMany()</strong> : <em>bool</em><br /><em>True if there can be many results from this relationship</em> |
| protected | <strong>abstract initialiseRelationship()</strong> : <em>void</em><br /><em>Apply the base single model constraints to the main query</em> |

*This class implements [\Somnambulist\ReadModels\Contracts\Queryable](#interface-somnambulistreadmodelscontractsqueryable)*

<hr />

### Class: \Somnambulist\ReadModels\Relationships\HasOneOrMany (abstract)

> Class HasOneOrMany

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>[\Somnambulist\ReadModels\ModelBuilder](#class-somnambulistreadmodelsmodelbuilder)</em> <strong>$builder</strong>, <em>[\Somnambulist\ReadModels\Model](#class-somnambulistreadmodelsmodel-abstract)</em> <strong>$parent</strong>, <em>\string</em> <strong>$foreignKey</strong>, <em>\string</em> <strong>$localKey</strong>)</strong> : <em>void</em><br /><em>Constructor.</em> |
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

