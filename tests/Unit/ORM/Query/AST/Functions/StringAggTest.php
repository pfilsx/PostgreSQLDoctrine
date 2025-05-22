<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\StringAgg;

/**
 * @see StringAgg
 */
final class StringAggTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['STRING_AGG(e.field)', false],
            ["STRING_AGG(e.field, ', ')", true],
            ["STRING_AGG(DISTINCT e.field, ', ')", true],
            ["STRING_AGG(DISTINCT e.field, ', ') FILTER (WHERE e.field IS NOT NULL)", true],
            ['STRING_AGG(e.field, e.field)', true],
            ['STRING_AGG(WHERE e.field, 1)', false],
            ["STRING_AGG(DISTINCT e.field, ', ') FILTER WHERE e.field IS NOT NULL", false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ["STRING_AGG(e.field, ', ')", "STRING_AGG(e.field, ', ')"],
            ["STRING_AGG(DISTINCT e.field, ', ')", "STRING_AGG(DISTINCT e.field, ', ')"],
            ["STRING_AGG(DISTINCT e.field, ', ') FILTER (WHERE e.field IS NOT NULL)", "STRING_AGG(DISTINCT e.field, ', ') FILTER ({WHERE})"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new StringAgg('STRING_AGG');
    }
}
