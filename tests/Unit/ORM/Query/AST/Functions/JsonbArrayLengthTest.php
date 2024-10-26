<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbArrayLength;

/**
 * @see JsonbArrayLength
 */
final class JsonbArrayLengthTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSONB_ARRAY_LENGTH(e.field)', true],
            ['JSONB_ARRAY_LENGTH()', false],
            ['JSONB_ARRAY_LENGTH(e.field, text)', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSONB_ARRAY_LENGTH(e.field)', 'JSONB_ARRAY_LENGTH(e.field)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonbArrayLength('JSONB_ARRAY_LENGTH');
    }
}
