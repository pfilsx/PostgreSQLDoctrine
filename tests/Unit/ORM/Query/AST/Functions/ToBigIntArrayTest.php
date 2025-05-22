<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToBigIntArray;

/**
 * @see ToBigIntArray
 */
final class ToBigIntArrayTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['BIGINT_ARRAY(:input)', true],
            ['BIGINT_ARRAY(1, 2, 3)', true],
            ["BIGINT_ARRAY('1', '2')", true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['BIGINT_ARRAY(:input)', 'ARRAY[?]::bigint[]'],
            ['BIGINT_ARRAY(1, 2, 3)', 'ARRAY[1, 2, 3]::bigint[]'],
            ["BIGINT_ARRAY('1', '2')", "ARRAY['1', '2']::bigint[]"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new ToBigIntArray('BIGINT_ARRAY');
    }
}
