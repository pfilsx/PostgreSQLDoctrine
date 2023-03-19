<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

/**
 * Implementation of PostgreSql TO_TSQUERY() function.
 *
 * @see https://www.postgresql.org/docs/current/textsearch-controls.html#TEXTSEARCH-PARSING-QUERIES
 *
 * @example TO_TSQUERY(entity.field)
 * @example TO_TSQUERY('some search query text')
 * @example TO_TSQUERY('english', entity.field)
 */
class ToTsQuery extends AbstractToTsFunction
{
    protected function getFunctionName(): string
    {
        return 'TO_TSQUERY';
    }
}
