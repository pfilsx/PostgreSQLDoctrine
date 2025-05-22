<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsQuery;

/**
 * @see ToTsQuery
 */
final class ToTsQueryTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['TO_TSQUERY()', false],
            ['TO_TSQUERY(e.field)', true],
            ['TO_TSQUERY(:text)', true],
            ["TO_TSQUERY('some text')", true],
            ["TO_TSQUERY('english', :text)", true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['TO_TSQUERY(e.field)', 'TO_TSQUERY(e.field)'],
            ['TO_TSQUERY(:text)', 'TO_TSQUERY(?)'],
            ["TO_TSQUERY('some text')", "TO_TSQUERY('some text')"],
            ["TO_TSQUERY('english', :text)", "TO_TSQUERY('english', ?)"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new ToTsQuery('TO_TSQUERY');
    }
}
