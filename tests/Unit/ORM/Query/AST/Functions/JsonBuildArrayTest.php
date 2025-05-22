<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonBuildArray;

/**
 * @see JsonBuildArray
 */
final class JsonBuildArrayTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSON_BUILD_ARRAY(e.field)', true],
            ['JSON_BUILD_ARRAY(e.field, e.field2, e.field3)', true],
            ['JSON_BUILD_ARRAY()', false],
            ['JSON_BUILD_ARRAY(1, text)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSON_BUILD_ARRAY(e.field)', 'JSON_BUILD_ARRAY(e.field)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonBuildArray('JSON_BUILD_ARRAY');
    }
}
