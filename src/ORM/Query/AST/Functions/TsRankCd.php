<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql TS_RANK_CD() function.
 *
 * @see https://www.postgresql.org/docs/current/textsearch-controls.html#TEXTSEARCH-RANKING
 *
 * @example TS_RANK_CD(entity.field, TO_TSQUERY('text'))
 * @example TS_RANK_CD(entity.field, TO_TSQUERY('text'), 32)
 * @example TS_RANK_CD(TO_TSVECTOR('some text'), TO_TSQUERY('text'), 32)
 */
class TsRankCd extends TsRank
{
    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf('TS_RANK_CD(%s, %s%s)',
            $this->vector->dispatch($sqlWalker),
            $this->query->dispatch($sqlWalker),
            $this->normalization !== null ? ', ' . $this->normalization->dispatch($sqlWalker) : ''
        );
    }
}
