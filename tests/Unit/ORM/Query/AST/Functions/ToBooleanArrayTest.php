<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToBooleanArray;

/**
 * @see ToBooleanArray
 */
final class ToBooleanArrayTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['BOOLEAN_ARRAY(:input)', true],
            ['BOOLEAN_ARRAY(1, 0, 1)', true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['BOOLEAN_ARRAY(:input)', 'ARRAY[?]::boolean[]'],
            ['BOOLEAN_ARRAY(1, 0, 1)', 'ARRAY[1, 0, 1]::boolean[]'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new ToBooleanArray('BOOLEAN_ARRAY');
    }
}
