<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Floor;

/**
 * @see Floor
 */
final class FloorTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['FLOOR(e.field)', true],
            ['FLOOR(:float)', true],
            ['FLOOR(0.5)', true],
            ['FLOOR()', false],
            ['FLOOR(e.field, text)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['FLOOR(e.field)', 'FLOOR(e.field)'],
            ['FLOOR(:float)', 'FLOOR(?)'],
            ['FLOOR(0.5)', 'FLOOR(0.5)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new Floor('FLOOR');
    }
}
