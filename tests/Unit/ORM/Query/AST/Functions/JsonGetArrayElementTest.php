<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonGetArrayElement;

/**
 * @see JsonGetArrayElement
 */
final class JsonGetArrayElementTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSON_GET_ARRAY_ELEMENT(e.field)', false],
            ['JSON_GET_ARRAY_ELEMENT(e.field, 1)', true],
            ['JSON_GET_ARRAY_ELEMENT(e.field, 1, 2)', true],
            ["JSON_GET_ARRAY_ELEMENT(e.field, 'a')", false],
            ['JSON_GET_ARRAY_ELEMENT(e.field, e.field2, e.field3)', false],
            ['JSON_GET_ARRAY_ELEMENT()', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSON_GET_ARRAY_ELEMENT(e.field, 1)', 'e.field->1'],
            ['JSON_GET_ARRAY_ELEMENT(e.field, 1, 2)', 'e.field->1->2'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonGetArrayElement('JSON_GET_ARRAY_ELEMENT');
    }
}
