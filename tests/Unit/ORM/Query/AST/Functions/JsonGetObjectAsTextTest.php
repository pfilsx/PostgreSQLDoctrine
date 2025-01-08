<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetObjectAsText;

/**
 * @see JsonGetObjectAsText
 */
final class JsonGetObjectAsTextTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSON_GET_OBJECT_AS_TEXT(e.field)', false],
            ["JSON_GET_OBJECT_AS_TEXT(e.field, '{a,b}')", true],
            ['JSON_GET_OBJECT_AS_TEXT(e.field, e.field2)', true],
            ['JSON_GET_OBJECT_AS_TEXT()', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ["JSON_GET_OBJECT_AS_TEXT(e.field, '{a,b}')", "(e.field #>> '{a,b}')"],
            ['JSON_GET_OBJECT_AS_TEXT(e.field, e.field2)', '(e.field #>> e.field2)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonGetObjectAsText('JSON_GET_OBJECT_AS_TEXT');
    }
}
