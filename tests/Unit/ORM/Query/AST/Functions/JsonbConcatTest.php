<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbConcat;

/**
 * @see JsonbConcat
 */
final class JsonbConcatTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSONB_CONCAT(e.field)', false],
            ['JSONB_CONCAT(e.field, e.field2)', true],
            ['JSONB_CONCAT(e.field, e.field2, e.field3)', false],
            ['JSONB_CONCAT()', false],
            ['JSONB_CONCAT(1, text)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSONB_CONCAT(e.field, e.field2)', '(e.field || e.field2)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonbConcat('JSONB_CONCAT');
    }
}
