<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Ceil;

/**
 * @see Ceil
 */
final class CeilTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['CEIL(e.field)', true],
            ['CEIL(:float)', true],
            ['CEIL(0.5)', true],
            ['CEIL()', false],
            ['CEIL(e.field, text)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['CEIL(e.field)', 'CEIL(e.field)'],
            ['CEIL(:float)', 'CEIL(?)'],
            ['CEIL(0.5)', 'CEIL(0.5)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new Ceil('CEIL');
    }
}
