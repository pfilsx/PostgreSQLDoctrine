<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Overlaps;

/**
 * @see Overlaps
 */
final class OverlapsTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['OVERLAPS(e.field)', false],
            ['OVERLAPS(e.field, e.field2)', true],
            ['OVERLAPS(e.field, e.field2, e.field3)', false],
            ['OVERLAPS()', false],
            ['OVERLAPS(1, text)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['OVERLAPS(e.field, e.field2)', '(e.field && e.field2)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new Overlaps('OVERLAPS');
    }
}
