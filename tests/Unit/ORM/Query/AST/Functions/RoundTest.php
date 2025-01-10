<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Round;

/**
 * @see Round
 */
final class RoundTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['ROUND()', false],
            ['ROUND(e.field)', true],
            ['ROUND(e.field, 2)', true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['ROUND(e.field)', 'ROUND(e.field)'],
            ['ROUND(e.field, 2)', 'ROUND(e.field, 2)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new Round('ROUND');
    }
}
