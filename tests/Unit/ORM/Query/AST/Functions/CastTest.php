<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\Cast;

/**
 * @see Cast
 */
final class CastTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['CAST(e.field AS text)', true],
            ['CAST(e.field AS int8)', true],
            ['CAST(e.field, text)', false],
            ['CAST(e.field AS :text)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['CAST(e.field AS text)', 'CAST(e.field AS text)'],
            ['CAST(e.field AS int8)', 'CAST(e.field AS int8)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new Cast('CAST');
    }
}
