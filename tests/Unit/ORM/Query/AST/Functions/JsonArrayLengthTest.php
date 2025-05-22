<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonArrayLength;

/**
 * @see JsonArrayLength
 */
final class JsonArrayLengthTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSON_ARRAY_LENGTH(e.field)', true],
            ['JSON_ARRAY_LENGTH()', false],
            ['JSON_ARRAY_LENGTH(e.field, text)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSON_ARRAY_LENGTH(e.field)', 'JSON_ARRAY_LENGTH(e.field)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonArrayLength('JSON_ARRAY_LENGTH');
    }
}
