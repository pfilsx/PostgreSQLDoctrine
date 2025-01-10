<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Random;

/**
 * @see Random
 */
final class RandomTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['RANDOM()', true],
            ['RANDOM(e.field)', false],
            ['RANDOM(1)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['RANDOM()', 'RANDOM()'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new Random('RANDOM');
    }
}
