Available types
===============

| PostgreSQL type | Register as | Implementation                                                                                  |
|-----------------|-------------|-------------------------------------------------------------------------------------------------|
| _bool           | bool[]      | [Pfilsx\PostgreSQLDoctrine\DBAL\Type\BooleanArrayType](../src/DBAL/Type/BooleanArrayType.php)   |
| _int2           | smallint[]  | [Pfilsx\PostgreSQLDoctrine\DBAL\Type\SmallIntArrayType](../src/DBAL/Type/SmallIntArrayType.php) | 
| _int4           | integer[]   | [Pfilsx\PostgreSQLDoctrine\DBAL\Type\IntegerArrayType](../src/DBAL/Type/IntegerArrayType.php)   | 
| _int8           | bigint[]    | [Pfilsx\PostgreSQLDoctrine\DBAL\Type\BigIntArrayType](../src/DBAL/Type/BigIntArrayType.php)     | 
| _text           | text[]      | [Pfilsx\PostgreSQLDoctrine\DBAL\Type\TextArrayType](../src/DBAL/Type/TextArrayType.php)         |
| tsvector        | tsvector    | [Pfilsx\PostgreSQLDoctrine\DBAL\Type\TsVectorType](../src/DBAL/Type/TsVectorType.php)           |

Integration with Doctrine
=========================

```php 
<?php

use Doctrine\DBAL\Types\Type;

Type::addType('bool[]', 'Pfilsx\PostgreSQLDoctrine\DBAL\Type\BooleanArrayType');
Type::addType('smallint[]', 'Pfilsx\PostgreSQLDoctrine\DBAL\Type\SmallIntArrayType');
Type::addType('integer[]', 'Pfilsx\PostgreSQLDoctrine\DBAL\Type\IntegerArrayType');
Type::addType('bigint[]', 'Pfilsx\PostgreSQLDoctrine\DBAL\Type\BigIntArrayType');
Type::addType('text[]', 'Pfilsx\PostgreSQLDoctrine\DBAL\Type\TextArrayType');
Type::addType('tsvector', 'Pfilsx\PostgreSQLDoctrine\DBAL\Type\TsVectorType');

// ...

$platform = $em->getConnection()->getDatabasePlatform();
$platform->registerDoctrineTypeMapping('bool[]', 'bool[]');
$platform->registerDoctrineTypeMapping('_bool', 'bool[]');
$platform->registerDoctrineTypeMapping('integer[]', 'integer[]');
$platform->registerDoctrineTypeMapping('_int4', 'integer[]');
$platform->registerDoctrineTypeMapping('bigint[]', 'bigint[]');
$platform->registerDoctrineTypeMapping('_int8', 'bigint[]');
$platform->registerDoctrineTypeMapping('text[]', 'text[]');
$platform->registerDoctrineTypeMapping('_text', 'text[]');
$platform->registerDoctrineTypeMapping('tsvector', 'tsvector');
```

Integration with Symfony
=========================

```yaml
# config/packages/doctrine.yaml
doctrine:
    dbal:
        types: 
            bool[]: Pfilsx\PostgreSQLDoctrine\DBAL\Type\BooleanArrayType
            smallint[]: Pfilsx\PostgreSQLDoctrine\DBAL\Type\SmallIntArrayType
            integer[]: Pfilsx\PostgreSQLDoctrine\DBAL\Type\IntegerArrayType
            bigint[]: Pfilsx\PostgreSQLDoctrine\DBAL\Type\BigIntArrayType
            text[]: Pfilsx\PostgreSQLDoctrine\DBAL\Type\TextArrayType
            tsvector: Pfilsx\PostgreSQLDoctrine\DBAL\Type\TsVectorType
            
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
            tsvector: tsvector
        # or only for specific connection
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
                    tsvector: tsvector
```