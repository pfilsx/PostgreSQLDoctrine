<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsQuery;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsMatch;

/**
 * @see TsMatch
 */
final class TsMatchTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['TS_MATCH()', false],
            ["TS_MATCH(e.field, TO_TSQUERY('text'))", true],
            ["TS_MATCH('text', TO_TSQUERY('text'))", true],
            ["TS_MATCH(:text, TO_TSQUERY('text'))", true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ["TS_MATCH(e.field, TO_TSQUERY('text'))", '(e.field @@ {FUNC})'],
            ["TS_MATCH('text', TO_TSQUERY('text'))", "('text' @@ {FUNC})"],
            ["TS_MATCH(:text, TO_TSQUERY('text'))", '(? @@ {FUNC})'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new TsMatch('TS_MATCH');
    }

    /**
     * {@inheritdoc}
     */
    protected function getAdditionalCustomFunctions(): array
    {
        return [
            'TO_TSQUERY' => ToTsQuery::class,
        ];
    }
}
