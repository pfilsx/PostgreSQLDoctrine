<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\PhraseToTsQuery;

/**
 * @see PhraseToTsQuery
 */
final class PhraseToTsQueryTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['PHRASETO_TSQUERY()', false],
            ['PHRASETO_TSQUERY(e.field)', true],
            ['PHRASETO_TSQUERY(:text)', true],
            ["PHRASETO_TSQUERY('some text')", true],
            ["PHRASETO_TSQUERY('english', :text)", true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['PHRASETO_TSQUERY(e.field)', 'PHRASETO_TSQUERY(e.field)'],
            ['PHRASETO_TSQUERY(:text)', 'PHRASETO_TSQUERY(?)'],
            ["PHRASETO_TSQUERY('some text')", "PHRASETO_TSQUERY('some text')"],
            ["PHRASETO_TSQUERY('english', :text)", "PHRASETO_TSQUERY('english', ?)"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new PhraseToTsQuery('PHRASETO_TSQUERY');
    }
}
