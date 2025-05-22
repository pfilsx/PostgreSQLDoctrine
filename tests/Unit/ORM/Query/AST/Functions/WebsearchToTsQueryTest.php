<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\WebsearchToTsQuery;

/**
 * @see WebsearchToTsQuery
 */
final class WebsearchToTsQueryTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['WEBSEARCH_TO_TSQUERY()', false],
            ['WEBSEARCH_TO_TSQUERY(e.field)', true],
            ['WEBSEARCH_TO_TSQUERY(:text)', true],
            ["WEBSEARCH_TO_TSQUERY('some text')", true],
            ["WEBSEARCH_TO_TSQUERY('english', :text)", true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['WEBSEARCH_TO_TSQUERY(e.field)', 'WEBSEARCH_TO_TSQUERY(e.field)'],
            ['WEBSEARCH_TO_TSQUERY(:text)', 'WEBSEARCH_TO_TSQUERY(?)'],
            ["WEBSEARCH_TO_TSQUERY('some text')", "WEBSEARCH_TO_TSQUERY('some text')"],
            ["WEBSEARCH_TO_TSQUERY('english', :text)", "WEBSEARCH_TO_TSQUERY('english', ?)"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new WebsearchToTsQuery('WEBSEARCH_TO_TSQUERY');
    }
}
