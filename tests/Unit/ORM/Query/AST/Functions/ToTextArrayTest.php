<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTextArray;

/**
 * @see ToTextArray
 */
final class ToTextArrayTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['TEXT_ARRAY(:input)', true],
            ['TEXT_ARRAY(1, 2, 3)', true],
            ["TEXT_ARRAY('test1', 'test2')", true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['TEXT_ARRAY(:input)', 'ARRAY[?]::text[]'],
            ['TEXT_ARRAY(1, 2, 3)', 'ARRAY[1, 2, 3]::text[]'],
            ["TEXT_ARRAY('test1', 'test2')", "ARRAY['test1', 'test2']::text[]"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new ToTextArray('TEXT_ARRAY');
    }
}
