<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetFieldAsText;

/**
 * @see JsonGetFieldAsText
 */
final class JsonGetFieldAsTextTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSON_GET_FIELD_AS_TEXT(e.field)', false],
            ["JSON_GET_FIELD_AS_TEXT(e.field, 'a')", true],
            ["JSON_GET_FIELD_AS_TEXT(e.field, e.field2, 'a')", true],
            ['JSON_GET_FIELD_AS_TEXT()', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ["JSON_GET_FIELD_AS_TEXT(e.field, 'a')", "e.field->>'a'"],
            ["JSON_GET_FIELD_AS_TEXT(e.field, e.field2, 'a')", "e.field->e.field2->>'a'"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonGetFieldAsText('JSON_GET_FIELD_AS_TEXT');
    }
}
