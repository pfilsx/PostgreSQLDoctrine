<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsQuery;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsVector;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsRankCd;

/**
 * @see TsRankCd
 */
final class TsRankCdTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['TS_RANK_CD()', false],
            ["TS_RANK_CD(e.field, TO_TSQUERY('text'))", true],
            ["TS_RANK_CD('text', TO_TSQUERY('text'))", true],
            ["TS_RANK_CD(:text, TO_TSQUERY('text'))", true],
            ["TS_RANK_CD(e.field, TO_TSQUERY('text'), 32)", true],
            ["TS_RANK_CD(TO_TSVECTOR(e.field), TO_TSQUERY('text'), 32)", true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ["TS_RANK_CD(e.field, TO_TSQUERY('text'))", 'TS_RANK_CD(e.field, {FUNC})'],
            ["TS_RANK_CD('text', TO_TSQUERY('text'))", "TS_RANK_CD('text', {FUNC})"],
            ["TS_RANK_CD(:text, TO_TSQUERY('text'))", 'TS_RANK_CD(?, {FUNC})'],
            ["TS_RANK_CD(e.field, TO_TSQUERY('text'), 32)", 'TS_RANK_CD(e.field, {FUNC}, 32)'],
            ["TS_RANK_CD(TO_TSVECTOR(e.field), TO_TSQUERY('text'), 32)", 'TS_RANK_CD({FUNC}, {FUNC}, 32)'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new TsRankCd('TS_RANK_CD');
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
