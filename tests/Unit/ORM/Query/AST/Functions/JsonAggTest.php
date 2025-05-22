<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonAgg;

/**
 * @see JsonAgg
 */
final class JsonAggTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSON_AGG(entity.field)', true],
            ['JSON_AGG(DISTINCT entity.field)', true],
            ['JSON_AGG(entity.field) FILTER (WHERE entity.field IS NOT NULL)', true],
            ['JSON_AGG(DISTINCT entity.field) FILTER (WHERE entity.field IS NOT NULL)', true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSON_AGG(entity.field)', 'JSON_AGG(entity.field)'],
            ['JSON_AGG(DISTINCT entity.field)', 'JSON_AGG(DISTINCT entity.field)'],
            ['JSON_AGG(entity.field) FILTER (WHERE entity.field IS NOT NULL)', 'JSON_AGG(entity.field) FILTER ({WHERE})'],
            ['JSON_AGG(DISTINCT entity.field) FILTER (WHERE entity.field IS NOT NULL)', 'JSON_AGG(DISTINCT entity.field) FILTER ({WHERE})'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonAgg('JSON_AGG');
    }
}
