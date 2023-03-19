Available functions
===================

| PostgreSQL function    | DQL function         | Implementation                                                                                                                |
|------------------------|----------------------|-------------------------------------------------------------------------------------------------------------------------------|
| ARRAY_AGG()            | ARRAY_AGG            | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ArrayAgg](../src/ORM/Query/AST/Functions/ArrayAgg.php)                     |
| JSON_AGG()             | JSON_AGG             | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonAgg](../src/ORM/Query/AST/Functions/JsonAgg.php)                       |
| JSONB_AGG()            | JSONB_AGG            | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbAgg](../src/ORM/Query/AST/Functions/JsonbAgg.php)                     |
| PHRASETO_TSQUERY()     | PHRASETO_TSQUERY     | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\PhraseToTsQuery](../src/ORM/Query/AST/Functions/PhraseToTsQuery.php)       |
| PLAINTO_TSQUERY()      | PLAINTO_TSQUERY      | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\PlainToTsQuery](../src/ORM/Query/AST/Functions/PlainToTsQuery.php)         |
| STRING_AGG()           | STRING_AGG           | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\StringAgg](../src/ORM/Query/AST/Functions/StringAgg.php)                   |
| TO_TSQUERY()           | TO_TSQUERY           | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsQuery](../src/ORM/Query/AST/Functions/ToTsQuery.php)                   |
| TO_TSVECTOR()          | TO_TSVECTOR          | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsVector](../src/ORM/Query/AST/Functions/ToTsVector.php)                 |
| TS_HEADLINE()          | TS_HEADLINE          | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsHeadline](../src/ORM/Query/AST/Functions/TsHeadline.php)                 |
| TS_RANK()              | TS_RANK              | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsRank](../src/ORM/Query/AST/Functions/TsRank.php)                         |
| TS_RANK_CD()           | TS_RANK_CD           | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsRankCd](../src/ORM/Query/AST/Functions/TsRankCd.php)                     |
| WEBSEARCH_TO_TSQUERY() | WEBSEARCH_TO_TSQUERY | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\WebsearchToTsQuery](../src/ORM/Query/AST/Functions/WebsearchToTsQuery.php) |

Available operators
===================

| PostgreSQL operator | DQL function            | Implementation                                                                                                                  |
|---------------------|-------------------------|---------------------------------------------------------------------------------------------------------------------------------|
| &#124;&#124;        | JSONB_CONCAT            | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbConcat](../src/ORM/Query/AST/Functions/JsonbConcat.php)                 |
| @>                  | JSONB_CONTAINS          | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbContains](../src/ORM/Query/AST/Functions/JsonbContains.php)             |
| ?                   | JSONB_KEY_EXISTS        | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbKeyExists](../src/ORM/Query/AST/Functions/JsonbKeyExists.php)           |
| -                   | JSONB_REMOVE            | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbRemove](../src/ORM/Query/AST/Functions/JsonbRemove.php)                 |
| ->                  | JSON_GET_FIELD          | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetField](../src/ORM/Query/AST/Functions/JsonGetField.php)               |
| ->>                 | JSON_GET_FIELD_AS_TEXT  | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetFieldAsText](../src/ORM/Query/AST/Functions/JsonGetFieldAsText.php)   |
| #>                  | JSON_GET_OBJECT         | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetObject](../src/ORM/Query/AST/Functions/JsonGetObject.php)             |
| #>>                 | JSON_GET_OBJECT_AS_TEXT | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetObjectAsText](../src/ORM/Query/AST/Functions/JsonGetObjectAsText.php) |
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