<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbAgg;

/**
 * @see JsonbAgg
 */
final class JsonbAggTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSONB_AGG(entity.field)', true],
            ['JSONB_AGG(DISTINCT entity.field)', true],
            ['JSONB_AGG(entity.field) FILTER (WHERE entity.field IS NOT NULL)', true],
            ['JSONB_AGG(DISTINCT entity.field) FILTER (WHERE entity.field IS NOT NULL)', true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSONB_AGG(entity.field)', 'JSONB_AGG(entity.field)'],
            ['JSONB_AGG(DISTINCT entity.field)', 'JSONB_AGG(DISTINCT entity.field)'],
            ['JSONB_AGG(entity.field) FILTER (WHERE entity.field IS NOT NULL)', 'JSONB_AGG(entity.field) FILTER ({WHERE})'],
            ['JSONB_AGG(DISTINCT entity.field) FILTER (WHERE entity.field IS NOT NULL)', 'JSONB_AGG(DISTINCT entity.field) FILTER ({WHERE})'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonbAgg('JSONB_AGG');
    }
}
