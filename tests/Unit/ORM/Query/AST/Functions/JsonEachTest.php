<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonEach;

/**
 * @see JsonEach
 */
final class JsonEachTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSON_EACH(e.field)', true],
            ['JSON_EACH(e.field, e.field2)', false],
            ['JSON_EACH()', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSON_EACH(e.field)', 'JSON_EACH(e.field)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonEach('JSON_EACH');
    }
}
