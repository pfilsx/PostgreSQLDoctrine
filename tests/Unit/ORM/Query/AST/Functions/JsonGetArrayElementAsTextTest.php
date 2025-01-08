<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetArrayElementAsText;

/**
 * @see JsonGetArrayElementAsText
 */
final class JsonGetArrayElementAsTextTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSON_GET_ARRAY_ELEMENT_AS_TEXT(e.field)', false],
            ['JSON_GET_ARRAY_ELEMENT_AS_TEXT(e.field, 1)', true],
            ['JSON_GET_ARRAY_ELEMENT_AS_TEXT(e.field, 1, 2)', true],
            ["JSON_GET_ARRAY_ELEMENT_AS_TEXT(e.field, 'a')", false],
            ['JSON_GET_ARRAY_ELEMENT_AS_TEXT(e.field, e.field2, e.field3)', false],
            ['JSON_GET_ARRAY_ELEMENT_AS_TEXT()', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSON_GET_ARRAY_ELEMENT_AS_TEXT(e.field, 1)', 'e.field->>1'],
            ['JSON_GET_ARRAY_ELEMENT_AS_TEXT(e.field, 1, 2)', 'e.field->1->>2'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonGetArrayElementAsText('JSON_GET_ARRAY_ELEMENT_AS_TEXT');
    }
}
