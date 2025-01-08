<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\JsonbEach;

/**
 * @see JsonbEach
 */
final class JsonbEachTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['JSONB_EACH(e.field)', true],
            ['JSONB_EACH(e.field, e.field2)', false],
            ['JSONB_EACH()', false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['JSONB_EACH(e.field)', 'JSONB_EACH(e.field)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new JsonbEach('JSONB_EACH');
    }
}
