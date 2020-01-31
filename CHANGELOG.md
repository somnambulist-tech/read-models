Change Log
==========

2020-01-31 - 1.1.1
------------------
 
 * addresses issues when using the external identity to load relationships
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
 
 * add exporting relationship attributes via with syntax
 * add couple of custom property accessors
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
