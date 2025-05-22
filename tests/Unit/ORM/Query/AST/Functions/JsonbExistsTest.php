<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbExists;

/**
 * @see JsonbExists
 */
final class JsonbExistsTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSONB_EXISTS(e.field)', false],
            ['JSONB_EXISTS(e.field, e.field2)', true],
            ["JSONB_EXISTS(e.field, 'a')", true],
            ['JSONB_EXISTS(e.field, e.field2, e.field3)', false],
            ['JSONB_EXISTS()', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSONB_EXISTS(e.field, e.field2)', 'JSONB_EXISTS(e.field, e.field2)'],
            ["JSONB_EXISTS(e.field, 'a')", "JSONB_EXISTS(e.field, 'a')"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonbExists('JSONB_EXISTS');
    }
}
