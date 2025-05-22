<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ArrayToJson;

/**
 * @see ArrayToJson
 */
final class ArrayToJsonTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['ARRAY_TO_JSON(entity.field)', true],
            ['ARRAY_TO_JSON(:arr)', true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['ARRAY_TO_JSON(entity.field)', 'ARRAY_TO_JSON(entity.field)'],
            ['ARRAY_TO_JSON(:arr)', 'ARRAY_TO_JSON(?)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new ArrayToJson('ARRAY_TO_JSON');
    }
}
