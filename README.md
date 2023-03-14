PostgreSQL Doctrine
==============

[![Latest Stable Version](http://poser.pugx.org/pfilsx/postgresql-doctrine/v)](https://packagist.org/packages/pfilsx/postgresql-doctrine)
[![PHP Version Require](http://poser.pugx.org/pfilsx/postgresql-doctrine/require/php)](https://packagist.org/packages/pfilsx/postgresql-doctrine)
[![Total Downloads](http://poser.pugx.org/pfilsx/postgresql-doctrine/downloads)](https://packagist.org/packages/pfilsx/postgresql-doctrine)

Description
------------

Provides extended Doctrine DBAL and Doctrine migration classes to allow you to use PostgreSQL 
specific features such as [enums](https://www.postgresql.org/docs/current/datatype-enum.html) or JSON(B) with Doctrine.

Features
--------
* PostgreSQL enums support in DBAL and migrations
* PHP8 enum support
* Fix creating [default schema in down migrations for pgsql](https://github.com/doctrine/dbal/issues/1110)
* [JSON(B) functions](https://www.postgresql.org/docs/current/functions-json.html) (in progress)
* JSON(B) types based on object models (in progress, requires symfony/serializer)
* [Trait](src/ORM/Trait/ExistsMethodRepositoryTrait.php) for easy use of [SELECT EXISTS(...)](https://www.postgresql.org/docs/current/functions-subquery.html#FUNCTIONS-SUBQUERY-EXISTS) in your entity repositories

Requirement
-----------
* PHP ^8.1
* doctrine/dbal ^3.5.1
* doctrine/migrations ^3.5.2
* symfony/serializer >=5.4.* (optional for json models)
* symfony/property-info >=5.4.* (optional)

Installation
------------

Open a command console, enter your project directory and execute the following command to download the latest version of this bundle:
```bash
composer require pfilsx/postgresql-doctrine
```

Usage
-----

Please refer [Doctrine DBAL](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/index.html) 
and [Doctrine Migrations](https://www.doctrine-project.org/projects/doctrine-migrations/en/3.5/index.html)
for instructions on how to override the default doctrine classes in your project.

Required steps:
1. Register [PostgreSQLDriverMiddleware.php](src/DBAL/Middleware/PostgreSQLDriverMiddleware.php) as driver middleware
2. Register [OrmSchemaProvider.php](src/Migrations/Provider/OrmSchemaProvider.php) as Doctrine\Migrations\Provider\SchemaProvider in Doctrine\Migrations\DependencyFactory

For Symfony integration see [PostgreSQLDoctrineBundle](https://github.com/pfilsx/PostgreSQLDoctrineBundle)

Documentation
-------------

* [ENUMS](docs/ENUMS.md)
* [Functions](docs/FUNCTIONS-AND-OPERATORS.md)

License
-------

This bundle is released under the MIT license.