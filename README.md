PostgreSQL Doctrine
==============

Description
------------

Provides extended Doctrine DBAL and Doctrine migration classes to allow you to use PostgreSQL 
specific features such as [enums](https://www.postgresql.org/docs/current/datatype-enum.html) with Doctrine.

Features
--------
* PostgreSQL enums support in DBAL and migrations
* PHP8 enum support
* Fix creating [default schema in down migrations for pgsql](https://github.com/doctrine/dbal/issues/1110)
* [JSON(B) functions](https://www.postgresql.org/docs/current/functions-json.html) (in progress)
* JSON(B) types based on object models (in progress, requires symfony/serializer)

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