<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonObjectKeys;

/**
 * @see JsonObjectKeys
 */
final class JsonObjectKeysTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSON_OBJECT_KEYS(e.field)', true],
            ['JSON_OBJECT_KEYS(e.field, e.field2)', false],
            ['JSON_OBJECT_KEYS()', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSON_OBJECT_KEYS(e.field)', 'JSON_OBJECT_KEYS(e.field)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonObjectKeys('JSON_OBJECT_KEYS');
    }
}
