<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbExists;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbRemove;

/**
 * @see JsonbExists
 */
final class JsonbRemoveTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSONB_REMOVE(e.field)', false],
            ['JSONB_REMOVE(e.field, e.field2)', true],
            ["JSONB_REMOVE(e.field, 'a')", true],
            ['JSONB_REMOVE(e.field, e.field2, e.field3)', false],
            ['JSONB_REMOVE()', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSONB_REMOVE(e.field, e.field2)', '(e.field - e.field2)'],
            ["JSONB_REMOVE(e.field, 'a')", "(e.field - 'a')"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonbRemove('JSONB_REMOVE');
    }
}
