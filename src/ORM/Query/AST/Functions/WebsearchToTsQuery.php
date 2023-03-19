<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

/**
 * Implementation of PostgreSql WEBSEARCH_TO_TSQUERY() function.
 *
 * @see https://www.postgresql.org/docs/current/textsearch-controls.html#TEXTSEARCH-PARSING-QUERIES
 *
 * @example WEBSEARCH_TO_TSQUERY(entity.field)
 * @example WEBSEARCH_TO_TSQUERY('some search query text')
 * @example WEBSEARCH_TO_TSQUERY('english', entity.field)
 */
class WebsearchToTsQuery extends AbstractToTsFunction
{
    protected function getFunctionName(): string
    {
        return 'WEBSEARCH_TO_TSQUERY';
    }
}
