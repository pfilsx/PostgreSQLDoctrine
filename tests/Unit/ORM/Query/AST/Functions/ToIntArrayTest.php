<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToIntArray;

/**
 * @see ToIntArray
 */
final class ToIntArrayTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['INT_ARRAY(:input)', true],
            ['INT_ARRAY(1, 2, 3)', true],
            ["INT_ARRAY('1', '2')", true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['INT_ARRAY(:input)', 'ARRAY[?]::integer[]'],
            ['INT_ARRAY(1, 2, 3)', 'ARRAY[1, 2, 3]::integer[]'],
            ["INT_ARRAY('1', '2')", "ARRAY['1', '2']::integer[]"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new ToIntArray('INT_ARRAY');
    }
}
