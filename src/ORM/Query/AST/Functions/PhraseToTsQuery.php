<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

/**
 * Implementation of PostgreSql PHRASETO_TSQUERY() function.
 *
 * @see https://www.postgresql.org/docs/current/textsearch-controls.html#TEXTSEARCH-PARSING-QUERIES
 *
 * @example PHRASETO_TSQUERY(entity.field)
 * @example PHRASETO_TSQUERY('some search query text')
 * @example PHRASETO_TSQUERY('english', entity.field)
 */
class PhraseToTsQuery extends AbstractToTsFunction
{
    protected function getFunctionName(): string
    {
        return 'PHRASETO_TSQUERY';
    }
}
