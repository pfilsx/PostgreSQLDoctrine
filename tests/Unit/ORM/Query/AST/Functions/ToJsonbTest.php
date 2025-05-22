<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToJsonb;

/**
 * @see ToJsonb
 */
final class ToJsonbTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['TO_JSONB(e.field)', true],
            ['TO_JSONB(:text)', true],
            ["TO_JSONB('{\"a\": 2}')", true],
            ['TO_JSONB()', false],
            ['TO_JSONB(e.field, text)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['TO_JSONB(e.field)', 'TO_JSONB(e.field)'],
            ['TO_JSONB(:text)', 'TO_JSONB(?)'],
            ["TO_JSONB('{\"a\": 2}')", "TO_JSONB('{\"a\": 2}')"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new ToJsonb('TO_JSONB');
    }
}
