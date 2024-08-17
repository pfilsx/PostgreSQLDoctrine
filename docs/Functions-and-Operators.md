# Available functions


| PostgreSQL function     | DQL function          | Implementation                                                                                                                |
|-------------------------|-----------------------|-------------------------------------------------------------------------------------------------------------------------------|
| ARRAY_AGG()             | ARRAY_AGG             | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ArrayAgg](../src/ORM/Query/AST/Functions/ArrayAgg.php)                     |
| ARRAY_TO_JSON()         | ARRAY_TO_JSON         | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ArrayToJson](../src/ORM/Query/AST/Functions/ArrayToJson.php)               |
| CAST()                  | CAST                  | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Cast](../src/ORM/Query/AST/Functions/Cast.php)                             |
| CEIL()                  | CEIL                  | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Ceil](../src/ORM/Query/AST/Functions/Ceil.php)                             |
| COUNT()                 | COUNT                 | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Count](../src/ORM/Query/AST/Functions/Count.php)                           |
| FLOOR()                 | FLOOR                 | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Floor](../src/ORM/Query/AST/Functions/Floor.php)                           |
| JSON_AGG()              | JSON_AGG              | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonAgg](../src/ORM/Query/AST/Functions/JsonAgg.php)                       |
| JSON_ARRAY_LENGTH()     | JSON_ARRAY_LENGTH     | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonArrayLength](../src/ORM/Query/AST/Functions/JsonArrayLength.php)       |
| JSONB_AGG()             | JSONB_AGG             | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbAgg](../src/ORM/Query/AST/Functions/JsonbAgg.php)                     |
| JSONB_ARRAY_LENGTH()    | JSONB_ARRAY_LENGTH    | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbArrayLength](../src/ORM/Query/AST/Functions/JsonbArrayLength.php)     |
| JSONB_BUILD_ARRAY()     | JSONB_BUILD_ARRAY     | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbBuildArray](../src/ORM/Query/AST/Functions/JsonbBuildArray.php)       |
| JSONB_BUILD_OBJECT()    | JSONB_BUILD_OBJECT    | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbBuildObject](../src/ORM/Query/AST/Functions/JsonbBuildObject.php)     |
| JSONB_EACH()            | JSONB_EACH            | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbEach](../src/ORM/Query/AST/Functions/JsonbEach.php)                   |
| JSONB_EACH_TEXT()       | JSONB_EACH_TEXT       | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbEachText](../src/ORM/Query/AST/Functions/JsonbEachText.php)           |
| JSONB_OBJECT_KEYS()     | JSONB_OBJECT_KEYS     | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbObjectKeys](../src/ORM/Query/AST/Functions/JsonbObjectKeys.php)       |
| JSON_BUILD_ARRAY()      | JSON_BUILD_ARRAY      | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonBuildArray](../src/ORM/Query/AST/Functions/JsonBuildArray.php)         |
| JSON_BUILD_OBJECT()     | JSON_BUILD_OBJECT     | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonBuildObject](../src/ORM/Query/AST/Functions/JsonBuildObject.php)       |
| JSON_EACH()             | JSON_EACH             | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonEach](../src/ORM/Query/AST/Functions/JsonEach.php)                     |
| JSON_EACH_TEXT()        | JSON_EACH_TEXT        | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonEachText](../src/ORM/Query/AST/Functions/JsonEachText.php)             |
| JSON_OBJECT_KEYS()      | JSON_OBJECT_KEYS      | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonObjectKeys](../src/ORM/Query/AST/Functions/JsonObjectKeys.php)         |
| PHRASETO_TSQUERY()      | PHRASETO_TSQUERY      | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\PhraseToTsQuery](../src/ORM/Query/AST/Functions/PhraseToTsQuery.php)       |
| PLAINTO_TSQUERY()       | PLAINTO_TSQUERY       | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\PlainToTsQuery](../src/ORM/Query/AST/Functions/PlainToTsQuery.php)         |
| RANDOM()                | RANDOM                | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Random](../src/ORM/Query/AST/Functions/Random.php)                         |
| ROUND()                 | ROUND                 | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Round](../src/ORM/Query/AST/Functions/Round.php)                           |
| STRING_AGG()            | STRING_AGG            | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\StringAgg](../src/ORM/Query/AST/Functions/StringAgg.php)                   |
| ARRAY[]                 | ARRAY                 | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToArray](../src/ORM/Query/AST/Functions/ToArray.php)                       |
| BIGINT[]                | BIGINT_ARRAY          | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToBigIntArray](../src/ORM/Query/AST/Functions/ToBigIntArray.php)           |
| BOOLEAN[]               | BOOLEAN_ARRAY         | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToBooleanArray](../src/ORM/Query/AST/Functions/ToBooleanArray.php)         |
| INT[]                   | INT_ARRAY             | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToIntArray](../src/ORM/Query/AST/Functions/ToIntArray.php)                 |
| TO_JSON()               | TO_JSON               | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToJson](../src/ORM/Query/AST/Functions/ToJson.php)                         |
| TO_JSONB()              | TO_JSONB              | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToJsonb](../src/ORM/Query/AST/Functions/ToJsonb.php)                       |
| SMALLINT[]              | SMALLINT_ARRAY        | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToSmallIntArray](../src/ORM/Query/AST/Functions/ToSmallIntArray.php)       |
| TEXT[]                  | TEXT_ARRAY            | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTextArray](../src/ORM/Query/AST/Functions/ToTextArray.php)               |
| TO_TSQUERY()            | TO_TSQUERY            | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsQuery](../src/ORM/Query/AST/Functions/ToTsQuery.php)                   |
| TO_TSVECTOR()           | TO_TSVECTOR           | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsVector](../src/ORM/Query/AST/Functions/ToTsVector.php)                 |
| TS_HEADLINE()           | TS_HEADLINE           | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsHeadline](../src/ORM/Query/AST/Functions/TsHeadline.php)                 |
| TS_RANK()               | TS_RANK               | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsRank](../src/ORM/Query/AST/Functions/TsRank.php)                         |
| TS_RANK_CD()            | TS_RANK_CD            | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsRankCd](../src/ORM/Query/AST/Functions/TsRankCd.php)                     |
| WEBSEARCH_TO_TS_QUERY() | WEBSEARCH_TO_TS_QUERY | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\WebsearchToTsQuery](../src/ORM/Query/AST/Functions/WebsearchToTsQuery.php) |

# Available operators

| PostgreSQL operator | DQL function            | Implementation                                                                                                                  |
|---------------------|-------------------------|---------------------------------------------------------------------------------------------------------------------------------|
| @>                  | CONTAINS                | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Contains](../src/ORM/Query/AST/Functions/Contains.php)                       |
| &#124;&#124;        | JSONB_CONCAT            | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbConcat](../src/ORM/Query/AST/Functions/JsonbConcat.php)                 |
| ?                   | JSONB_EXISTS            | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbExists](../src/ORM/Query/AST/Functions/JsonbExists.php)                 |
| -                   | JSONB_REMOVE            | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbRemove](../src/ORM/Query/AST/Functions/JsonbRemove.php)                 |
| ->                  | JSON_GET_FIELD          | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetField](../src/ORM/Query/AST/Functions/JsonGetField.php)               |
| ->>                 | JSON_GET_FIELD_AS_TEXT  | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetFieldAsText](../src/ORM/Query/AST/Functions/JsonGetFieldAsText.php)   |
| #>                  | JSON_GET_OBJECT         | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetObject](../src/ORM/Query/AST/Functions/JsonGetObject.php)             |
| #>>                 | JSON_GET_OBJECT_AS_TEXT | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetObjectAsText](../src/ORM/Query/AST/Functions/JsonGetObjectAsText.php) |
| &&                  | OVERLAPS                | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Overlaps](../src/ORM/Query/AST/Functions/Overlaps.php)                       |
| @@                  | TS_MATCH                | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsMatch](../src/ORM/Query/AST/Functions/TsMatch.php)                         |

Integration with Doctrine
=========================

```php
<?php

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;

$configuration = new Configuration();

$configuration->addCustomStringFunction('ARRAY_AGG', Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ArrayAgg::class);
$configuration->addCustomStringFunction('JSONB_CONCAT', Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbConcat::class);
$configuration->addCustomStringFunction('JSON_GET_FIELD', Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetField::class);
$configuration->addCustomStringFunction('TO_TSVECTOR', Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsVector::class);
$configuration->addCustomNumericFunction('TS_RANK', Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsRank::class);
$configuration->addCustomNumericFunction('TS_MATCH', Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsMatch::class);

// ...

$em = EntityManager::create($connection, $configuration);
```

Integration with Symfony
=========================

```yaml
# config/packages/doctrine.yaml
doctrine:
    orm:
        dql:
            string_functions:
                array_agg: Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ArrayAgg
                jsonb_concat: Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbConcat
                json_get_field: Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetField
                to_tsvector: Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsVector
                ts_match: Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsMatch
            numeric_functions:
                ts_rank: Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsRank
        # or only for specific em
        entity_managers:
            em_name:
                string_functions:
                    array_agg: Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ArrayAgg
                    jsonb_concat: Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbConcat
                    json_get_field: Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetField
                    to_tsvector: Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsVector
                    ts_match: Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsMatch
                numeric_functions:
                    ts_rank: Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsRank
```