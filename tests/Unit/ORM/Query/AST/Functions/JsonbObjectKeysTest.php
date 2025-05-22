<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbObjectKeys;

/**
 * @see JsonbObjectKeys
 */
final class JsonbObjectKeysTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSONB_OBJECT_KEYS(e.field)', true],
            ['JSONB_OBJECT_KEYS(e.field, e.field2)', false],
            ['JSONB_OBJECT_KEYS()', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSONB_OBJECT_KEYS(e.field)', 'JSONB_OBJECT_KEYS(e.field)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonbObjectKeys('JSONB_OBJECT_KEYS');
    }
}
