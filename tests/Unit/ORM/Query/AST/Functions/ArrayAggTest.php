<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ArrayAgg;

/**
 * @see ArrayAgg
 */
final class ArrayAggTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['ARRAY_AGG(e.field)', true],
            ['ARRAY_AGG(e.field, \'int[]\')', true],
            ['ARRAY_AGG(DISTINCT e.field)', true],
            ['ARRAY_AGG(DISTINCT e.field, \'json[]\') FILTER (WHERE e.field IS NOT NULL)', true],
            ['ARRAY_AGG(e.field, e.field)', false],
            ['ARRAY_AGG(WHERE e.field, \'json[]\')', false],
            ['ARRAY_AGG(DISTINCT e.field, \'json[]\') FILTER WHERE e.field IS NOT NULL', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['ARRAY_AGG(e.field)', 'ARRAY_AGG(e.field)'],
            ['ARRAY_AGG(e.field, \'int[]\')', 'ARRAY_AGG(e.field)'],
            ['ARRAY_AGG(DISTINCT e.field)', 'ARRAY_AGG(DISTINCT e.field)'],
            ['ARRAY_AGG(DISTINCT e.field, \'json[]\') FILTER (WHERE e.field IS NOT NULL)', 'ARRAY_AGG(DISTINCT e.field) FILTER ({WHERE})'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new ArrayAgg('ARRAY_AGG');
    }
}
