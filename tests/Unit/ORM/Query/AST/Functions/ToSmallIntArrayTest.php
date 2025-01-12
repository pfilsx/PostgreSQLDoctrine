<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToSmallIntArray;

/**
 * @see ToSmallIntArray
 */
final class ToSmallIntArrayTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['SMALLINT_ARRAY(:input)', true],
            ['SMALLINT_ARRAY(1, 2, 3)', true],
            ["SMALLINT_ARRAY('1', '2')", true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['SMALLINT_ARRAY(:input)', 'ARRAY[?]::smallint[]'],
            ['SMALLINT_ARRAY(1, 2, 3)', 'ARRAY[1, 2, 3]::smallint[]'],
            ["SMALLINT_ARRAY('1', '2')", "ARRAY['1', '2']::smallint[]"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new ToSmallIntArray('SMALLINT_ARRAY');
    }
}
