<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbBuildArray;

/**
 * @see JsonbBuildArray
 */
final class JsonbBuildArrayTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSONB_BUILD_ARRAY(e.field)', true],
            ['JSONB_BUILD_ARRAY(e.field, e.field2, e.field3)', true],
            ['JSONB_BUILD_ARRAY()', false],
            ['JSONB_BUILD_ARRAY(1, text)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSONB_BUILD_ARRAY(e.field)', 'JSONB_BUILD_ARRAY(e.field)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonbBuildArray('JSONB_BUILD_ARRAY');
    }
}
