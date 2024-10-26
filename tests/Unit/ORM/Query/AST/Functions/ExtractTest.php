<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Extract;

/**
 * @see Extract
 */
final class ExtractTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['EXTRACT(DAY FROM e.field)', true],
            ['EXTRACT(DAY FROM :param)', true],
            ['EXTRACT()', false],
            ['EXTRACT(e.field, DAY)', false],
            ['EXTRACT(DAY, e.field)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['EXTRACT(DAY FROM e.field)', 'EXTRACT(DAY FROM e.field)'],
            ['EXTRACT(DAY FROM :param)', 'EXTRACT(DAY FROM ?)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new Extract('EXTRACT');
    }
}
