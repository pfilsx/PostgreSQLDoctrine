<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetField;

/**
 * @see JsonGetField
 */
final class JsonGetFieldTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSON_GET_FIELD(e.field)', false],
            ["JSON_GET_FIELD(e.field, 'a')", true],
            ["JSON_GET_FIELD(e.field, e.field2, 'a')", true],
            ['JSON_GET_FIELD()', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ["JSON_GET_FIELD(e.field, 'a')", "e.field->'a'"],
            ["JSON_GET_FIELD(e.field, e.field2, 'a')", "e.field->e.field2->'a'"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonGetField('JSON_GET_FIELD');
    }
}
