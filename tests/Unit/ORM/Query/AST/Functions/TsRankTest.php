<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsQuery;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsVector;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsRank;

/**
 * @see TsRank
 */
final class TsRankTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['TS_RANK()', false],
            ["TS_RANK(e.field, TO_TSQUERY('text'))", true],
            ["TS_RANK('text', TO_TSQUERY('text'))", true],
            ["TS_RANK(:text, TO_TSQUERY('text'))", true],
            ["TS_RANK(e.field, TO_TSQUERY('text'), 32)", true],
            ["TS_RANK(TO_TSVECTOR(e.field), TO_TSQUERY('text'), 32)", true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ["TS_RANK(e.field, TO_TSQUERY('text'))", 'TS_RANK(e.field, {FUNC})'],
            ["TS_RANK('text', TO_TSQUERY('text'))", "TS_RANK('text', {FUNC})"],
            ["TS_RANK(:text, TO_TSQUERY('text'))", 'TS_RANK(?, {FUNC})'],
            ["TS_RANK(e.field, TO_TSQUERY('text'), 32)", 'TS_RANK(e.field, {FUNC}, 32)'],
            ["TS_RANK(TO_TSVECTOR(e.field), TO_TSQUERY('text'), 32)", 'TS_RANK({FUNC}, {FUNC}, 32)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new TsRank('TS_RANK');
    }

    /**
     * {@inheritdoc}
     */
    protected function getAdditionalCustomFunctions(): array
    {
        return [
            'TO_TSQUERY' => ToTsQuery::class,
            'TO_TSVECTOR' => ToTsVector::class,
        ];
    }
}
