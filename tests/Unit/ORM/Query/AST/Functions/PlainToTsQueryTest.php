<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\PlainToTsQuery;

/**
 * @see PlainToTsQuery
 */
final class PlainToTsQueryTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['PLAINTO_TSQUERY()', false],
            ['PLAINTO_TSQUERY(e.field)', true],
            ['PLAINTO_TSQUERY(:text)', true],
            ["PLAINTO_TSQUERY('some text')", true],
            ["PLAINTO_TSQUERY('english', :text)", true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['PLAINTO_TSQUERY(e.field)', 'PLAINTO_TSQUERY(e.field)'],
            ['PLAINTO_TSQUERY(:text)', 'PLAINTO_TSQUERY(?)'],
            ["PLAINTO_TSQUERY('some text')", "PLAINTO_TSQUERY('some text')"],
            ["PLAINTO_TSQUERY('english', :text)", "PLAINTO_TSQUERY('english', ?)"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new PlainToTsQuery('PLAINTO_TSQUERY');
    }
}
