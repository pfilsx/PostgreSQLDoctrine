<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonBuildObject;

/**
 * @see JsonBuildObject
 */
final class JsonBuildObjectTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSON_BUILD_OBJECT(e.field)', true],
            ['JSON_BUILD_OBJECT(e.field, e.field2)', true],
            ["JSON_BUILD_OBJECT('key1', e.field)", true],
            ['JSON_BUILD_OBJECT()', false],
            ['JSON_BUILD_OBJECT(1, text)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSON_BUILD_OBJECT(e.field)', 'JSON_BUILD_OBJECT(e.field)'],
            ['JSON_BUILD_OBJECT(e.field, e.field2)', 'JSON_BUILD_OBJECT(e.field,e.field2)'],
            ["JSON_BUILD_OBJECT('key1', e.field)", "JSON_BUILD_OBJECT('key1',e.field)"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonBuildObject('JSON_BUILD_OBJECT');
    }
}
