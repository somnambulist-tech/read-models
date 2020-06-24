Change Log
==========

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
