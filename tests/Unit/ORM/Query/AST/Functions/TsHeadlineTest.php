<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsQuery;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\TsHeadline;

/**
 * @see TsHeadline
 */
final class TsHeadlineTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['TS_HEADLINE()', false],
            ["TS_HEADLINE(e.field, TO_TSQUERY('text'))", true],
            ["TS_HEADLINE('text', TO_TSQUERY('text'))", true],
            ["TS_HEADLINE(:text, TO_TSQUERY('text'))", true],
            ["TS_HEADLINE('english', e.field, TO_TSQUERY('text'))", true],
            ["TS_HEADLINE('english', e.field, TO_TSQUERY('text'), 'MaxWords=7, MinWords=3')", true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ["TS_HEADLINE(e.field, TO_TSQUERY('text'))", 'TS_HEADLINE(e.field, {FUNC})'],
            ["TS_HEADLINE('text', TO_TSQUERY('text'))", "TS_HEADLINE('text', {FUNC})"],
            ["TS_HEADLINE(:text, TO_TSQUERY('text'))", 'TS_HEADLINE(?, {FUNC})'],
            ["TS_HEADLINE('english', e.field, TO_TSQUERY('text'))", "TS_HEADLINE('english', e.field, {FUNC})"],
            ["TS_HEADLINE('english', e.field, TO_TSQUERY('text'), 'MaxWords=7, MinWords=3')", "TS_HEADLINE('english', e.field, {FUNC}, 'MaxWords=7, MinWords=3')"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new TsHeadline('TS_HEADLINE');
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
