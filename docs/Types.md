Available types
===============

| PostgreSQL type | Register as | Implementation                                                                          |
|-----------------|-------------|-----------------------------------------------------------------------------------------|
| _bool           | bool[]      | [Pfilsx\PostgreSQLDoctrine\DBAL\Type\BooleanArray](../src/DBAL/Type/BooleanArray.php)   |
| _int2           | smallint[]  | [Pfilsx\PostgreSQLDoctrine\DBAL\Type\SmallIntArray](../src/DBAL/Type/SmallIntArray.php) | 
| _int4           | integer[]   | [Pfilsx\PostgreSQLDoctrine\DBAL\Type\IntegerArray](../src/DBAL/Type/IntegerArray.php)   | 
| _int8           | bigint[]    | [Pfilsx\PostgreSQLDoctrine\DBAL\Type\BigIntArray](../src/DBAL/Type/BigIntArray.php)     | 
| _text           | text[]      | [Pfilsx\PostgreSQLDoctrine\DBAL\Type\TextArray](../src/DBAL/Type/TextArray.php)         |

Integration with Doctrine
=========================

```php 
<?php

use Doctrine\DBAL\Types\Type;

Type::addType('bool[]', 'Pfilsx\PostgreSQLDoctrine\DBAL\Type\BooleanArray');
Type::addType('smallint[]', 'Pfilsx\PostgreSQLDoctrine\DBAL\Type\SmallIntArray');
Type::addType('integer[]', 'Pfilsx\PostgreSQLDoctrine\DBAL\Type\IntegerArray');
Type::addType('bigint[]', 'Pfilsx\PostgreSQLDoctrine\DBAL\Type\BigIntArray');
Type::addType('text[]', 'Pfilsx\PostgreSQLDoctrine\DBAL\Type\TextArray');

// ...

$platform = $em->getConnection()->getDatabasePlatform();
$platform->registerDoctrineTypeMapping('bool[]','bool[]');
$platform->registerDoctrineTypeMapping('_bool','bool[]');
$platform->registerDoctrineTypeMapping('integer[]','integer[]');
$platform->registerDoctrineTypeMapping('_int4','integer[]');
$platform->registerDoctrineTypeMapping('bigint[]','bigint[]');
$platform->registerDoctrineTypeMapping('_int8','bigint[]');
$platform->registerDoctrineTypeMapping('text[]','text[]');
$platform->registerDoctrineTypeMapping('_text','text[]');
```

Integration with Symfony
=========================
```yaml
# config/packages/doctrine.yaml
doctrine:
    dbal:
        types: 
            bool[]: Pfilsx\PostgreSQLDoctrine\DBAL\Type\BooleanArray
            smallint[]: Pfilsx\PostgreSQLDoctrine\DBAL\Type\SmallIntArray
            integer[]: Pfilsx\PostgreSQLDoctrine\DBAL\Type\IntegerArray
            bigint[]: Pfilsx\PostgreSQLDoctrine\DBAL\Type\BigIntArray
            text[]: Pfilsx\PostgreSQLDoctrine\DBAL\Type\TextArray
            
        mapping_types:
            bool[]: bool[]
            _bool: bool[]
            smallint[]: smallint[]
            _int2: smallint[]
            integer[]: integer[]
            _int4: integer[]
            bigint[]: bigint[]
            _int8: bigint[]
            text[]: text[]
            _text: text[]
        # or
        connections:
            connection_name:
                mapping_types:
                    bool[]: bool[]
                    _bool: bool[]
                    smallint[]: smallint[]
                    _int2: smallint[]
                    integer[]: integer[]
                    _int4: integer[]
                    bigint[]: bigint[]
                    _int8: bigint[]
                    text[]: text[]
                    _text: text[]
```