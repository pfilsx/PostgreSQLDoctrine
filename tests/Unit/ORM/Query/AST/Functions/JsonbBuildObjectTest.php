<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbBuildObject;

/**
 * @see JsonbBuildObject
 */
final class JsonbBuildObjectTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSONB_BUILD_OBJECT(e.field)', true],
            ['JSONB_BUILD_OBJECT(e.field, e.field2)', true],
            ["JSONB_BUILD_OBJECT('key1', e.field)", true],
            ['JSONB_BUILD_OBJECT()', false],
            ['JSONB_BUILD_OBJECT(1, text)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSONB_BUILD_OBJECT(e.field)', 'JSONB_BUILD_OBJECT(e.field)'],
            ['JSONB_BUILD_OBJECT(e.field, e.field2)', 'JSONB_BUILD_OBJECT(e.field,e.field2)'],
            ["JSONB_BUILD_OBJECT('key1', e.field)", "JSONB_BUILD_OBJECT('key1',e.field)"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonbBuildObject('JSONB_BUILD_OBJECT');
    }
}
