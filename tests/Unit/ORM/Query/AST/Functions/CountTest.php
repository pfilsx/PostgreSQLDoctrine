<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Count;

/**
 * @see Count
 */
final class CountTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['COUNT(entity.field)', true],
            ['COUNT(DISTINCT entity.field)', true],
            ['COUNT(entity.field) FILTER (WHERE entity.field IS NOT NULL)', true],
            ['COUNT(DISTINCT entity.field) FILTER (WHERE entity.field IS NOT NULL)', true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['COUNT(entity.field)', 'COUNT(entity.field)'],
            ['COUNT(DISTINCT entity.field)', 'COUNT(DISTINCT entity.field)'],
            ['COUNT(entity.field) FILTER (WHERE entity.field IS NOT NULL)', 'COUNT(entity.field) FILTER ({WHERE})'],
            ['COUNT(DISTINCT entity.field) FILTER (WHERE entity.field IS NOT NULL)', 'COUNT(DISTINCT entity.field) FILTER ({WHERE})'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new Count('COUNT');
    }
}
