<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToArray;

/**
 * @see ToArray
 */
final class ToArrayTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['ARRAY(:input)', true],
            ['ARRAY(1, 2, 3)', true],
            ["ARRAY('test1', 'test2')", true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['ARRAY(:input)', 'ARRAY[?]'],
            ['ARRAY(1, 2, 3)', 'ARRAY[1, 2, 3]'],
            ["ARRAY('test1', 'test2')", "ARRAY['test1', 'test2']"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new ToArray('ARRAY');
    }
}
