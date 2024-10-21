<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Contains;

/**
 * @see Contains
 */
final class ContainsTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['CONTAINS(entity.field, entity2.field)', true],
            ['CONTAINS(entity.field, :text)', true],
            ['CONTAINS(entity.field, \'text\')', true],
            ['CONTAINS(entity.field)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['CONTAINS(entity.field, entity2.field)', '(entity.field @> entity2.field)'],
            ['CONTAINS(entity.field, :text)', '(entity.field @> ?::jsonb)'],
            ['CONTAINS(entity.field, \'text\')', '(entity.field @> \'text\'::jsonb)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new Contains('CONTAINS');
    }
}
