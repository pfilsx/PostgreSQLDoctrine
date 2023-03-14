Available functions
===================

| PostgreSQL function | DQL function | Implementation                                                                                              |
|---------------------|--------------|-------------------------------------------------------------------------------------------------------------|
| ARRAY_AGG()         | ARRAY_AGG    | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ArrayAgg](../src/ORM/Query/AST/Functions/ArrayAgg.php)   |
| JSON_AGG()          | JSON_AGG     | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonAgg](../src/ORM/Query/AST/Functions/JsonAgg.php)     |
| JSONB_AGG()         | JSONB_AGG    | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbAgg](../src/ORM/Query/AST/Functions/JsonbAgg.php)   |
| STRING_AGG()        | STRING_AGG   | [Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\StringAgg](../src/ORM/Query/AST/Functions/StringAgg.php) |

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