<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToJson;

/**
 * @see ToJson
 */
final class ToJsonTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['TO_JSON(e.field)', true],
            ['TO_JSON(:text)', true],
            ["TO_JSON('{\"a\": 2}')", true],
            ['TO_JSON()', false],
            ['TO_JSON(e.field, text)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['TO_JSON(e.field)', 'TO_JSON(e.field)'],
            ['TO_JSON(:text)', 'TO_JSON(?)'],
            ["TO_JSON('{\"a\": 2}')", "TO_JSON('{\"a\": 2}')"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new ToJson('TO_JSON');
    }
}
