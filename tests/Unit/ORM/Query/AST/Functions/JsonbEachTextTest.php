<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbEachText;

/**
 * @see JsonbEachText
 */
final class JsonbEachTextTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSONB_EACH_TEXT(e.field)', true],
            ['JSONB_EACH_TEXT(e.field, e.field2)', false],
            ['JSONB_EACH_TEXT()', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSONB_EACH_TEXT(e.field)', 'JSONB_EACH_TEXT(e.field)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonbEachText('JSONB_EACH_TEXT');
    }
}
