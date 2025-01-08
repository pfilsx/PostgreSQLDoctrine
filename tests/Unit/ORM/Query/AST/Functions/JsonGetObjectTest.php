<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetObject;

/**
 * @see JsonGetObject
 */
final class JsonGetObjectTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSON_GET_OBJECT(e.field)', false],
            ["JSON_GET_OBJECT(e.field, '{a,b}')", true],
            ['JSON_GET_OBJECT(e.field, e.field2)', true],
            ['JSON_GET_OBJECT()', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ["JSON_GET_OBJECT(e.field, '{a,b}')", "(e.field #> '{a,b}')"],
            ['JSON_GET_OBJECT(e.field, e.field2)', '(e.field #> e.field2)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonGetObject('JSON_GET_OBJECT');
    }
}
