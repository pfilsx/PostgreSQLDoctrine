<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

/**
 * Implementation of PostgreSql PLAINTO_TSQUERY() function.
 *
 * @see https://www.postgresql.org/docs/current/textsearch-controls.html#TEXTSEARCH-PARSING-QUERIES
 *
 * @example PLAINTO_TSQUERY(entity.field)
 * @example PLAINTO_TSQUERY('some search query text')
 * @example PLAINTO_TSQUERY('english', entity.field)
 */
class PlainToTsQuery extends AbstractToTsFunction
{
    protected function getFunctionName(): string
    {
        return 'PLAINTO_TSQUERY';
    }
}
