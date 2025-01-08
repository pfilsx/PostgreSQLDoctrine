<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonEachText;

/**
 * @see JsonEachText
 */
final class JsonEachTextTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSON_EACH_TEXT(e.field)', true],
            ['JSON_EACH_TEXT(e.field, e.field2)', false],
            ['JSON_EACH_TEXT()', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSON_EACH_TEXT(e.field)', 'JSON_EACH_TEXT(e.field)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonEachText('JSON_EACH_TEXT');
    }
}
