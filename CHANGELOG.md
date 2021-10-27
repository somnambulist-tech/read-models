Change Log
==========

2021-10-27 - 3.1.0
------------------

 * fix bug in `GenerateRelationshipsToEagerLoad`

2021-10-05
----------

 * deprecate passing an array as first argument on `with()`s
 * use more PHP8 syntax in classes

2021-01-25
----------

 * fix spelling error

2021-01-21 - 3.0.0
------------------

 * require PHP 8
 * update to collection 5.0, domain 4.0

2020-12-18 - 2.2.0
------------------

 * add bound parameter count check on relationships to prevent running queries without bound args 

2020-10-22
----------

 * update DBAL usages to remove deprecated methods

2020-09-23 - 2.1.3
------------------

 * fix bug in relationships not applying default constraint when accessing via method call
   e.g.: `$user->profile()` vs `$user->profile`; the method call was not triggering `addConstraints()`

2020-09-22 - 2.1.2
------------------

 * fix bug in identity map where not all identity values are cast to strings during array operations

2020-09-17 - 2.1.1
------------------

 * fix bug in identity map; creating inverse relationship was generating incorrect mappings
 * fix bug in identity map; fetching related identities was returning the marker, not identity

2020-09-12 - 2.1.0
------------------

 * allow collection class to be overridden per model

2020-09-09
----------

 * remove `AttributeCasterException` not needed

2020-09-07 - 1.4.1
------------------

 * fix bug where `getParameter*` method calls on `ModelBuilder` are not returning the result

2020-09-07 - 2.0.0
------------------

 * re-namespace to Somnambulist\Components\ReadModels
 * release initial 2.0.0

2020-09-05
----------

 * add support for local scopes on `Model` for commonly used queries
 * add support for correlated sub-queries on select by passing ModelBuilders
 * add support for callbacks on `select()` and `where()` of `ModelBuilder`
 * clean up various files
 * fix bugs in parameter method pass through on `ModelBuilder`

2020-09-04
----------

 * refactor relationship loading
 * refactor `ModelBuilder` to set query itself instead of being passed in
 * remove `__get` meta from `Model` in favour of renamed `meta()` method

2020-09-03
----------

 * update to PHP 7.4 and use 7.4 syntax
 * refactor `Model` to extract attribute handling
 * refactor `Configurator` to a `Manager` that can be dependency injected via a container
 * refactor `Model::connections` to use a `ConnectionManager`
 * refactor usage of identity map to simplify `Model`
 * refactor casting / embeds to use only casters
 * remove static methods / properties for the configuration from `Model`
 * update all unit tests
 * require `table` be defined on `Model`

2020-06-28 - 1.4.0
------------------

 * update dependency version constraints; raising somnambulist/collection to 3/4

2020-06-24 - 1.3.0
------------------

 * update dependency version constraints; raising pragmarx/ia-str to 6/7

2020-06-24 - 1.2.5
------------------

 * fix schema prefixes on table names on `BelongsToMany` joins

2020-06-24 - 1.2.4
------------------

 * fix method calls when setting up pagination; calling `setCurrentPage` before `setMaxPerPage`
   causes a failure with pagerfanta > 2.1.3.

2020-05-13 - 1.2.3
------------------

 * attempt to fix #3: slow pagination queries by using derived select of main query
 * fix incorrect group by counting in `PaginatorAdapter`

2020-05-12 - 1.2.2
------------------

 * correct the namespace for the `JsonEncodingException`

2020-04-30 - 1.2.1
------------------

 * fix bug in `createParameterPlaceholderKey` where not all characters are replaced
 * fix bug where eager loading relationships loses any defined relationship constraints
 * refactor relationships to defer addition of default constraints until relationship fetch

2020-03-25 - 1.2.0
------------------

 * add `IdentityMapClearerMessengerSubscriber` to clear the identity map when using SF Messenger

2020-02-05 - 1.1.4
------------------

 * fix SF5 compatibility in the kernel subscriber (again)

2020-02-05 - 1.1.3
------------------

 * fix SF5 compatibility in the kernel subscriber

2020-02-04 - 1.1.2
------------------

 * fix not found exception should use primary key, not table name

2020-01-31 - 1.1.1
------------------
 
 * address issues when using the external identity to load relationships
 * fix bug in `HasOne` where it would assign multiple results instead of the first matching
 * fix bug in `ModelIdentityMap::getRelatedIdentitiesFor` that would not check external identity

2019-09-03 - 1.1.0
------------------

 * added `findBy`, `findOneBy`, `fetchFirstOrNull` helper methods to ModelBuilder

2019-09-03 - 1.0.2
------------------

 * fix bug using wrong class name in DoctrineTypeCaster

2019-07-22 - 1.0.1
------------------

 * fix bug exporting attributes via relationship not preserving model export options

2019-07-22 - 1.0.0
------------------

 * stable release

2019-07-20 - 0.3.0
------------------
 
 * add exporting relationship attributes via the same syntax as `with`
 * add a couple of custom property accessors
 * refactor metadata methods to ModelMetadata class
 * move IdentityMap to a singleton
 * remove the ModelBuilder pass through from Model

2019-07-15 - 0.1.6
------------------

 * multiple bug fix releases
 * halted tagging while working on the bugs
 
2019-07-14
----------

 * multiple bug fix releases

2019-07-13 - 0.1.0
------------------

 * alpha release to test functionality in a real project

2019-07-04
----------

 * initial commit
