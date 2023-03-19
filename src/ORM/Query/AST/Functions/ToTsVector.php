<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

/**
 * Implementation of PostgreSql TO_TSVECTOR() function.
 *
 * @see https://www.postgresql.org/docs/current/textsearch-controls.html#TEXTSEARCH-PARSING-DOCUMENTS
 *
 * @example TO_TSVECTOR(entity.field)
 * @example TO_TSVECTOR('some text')
 * @example TO_TSVECTOR('english', entity.field)
 */
class ToTsVector extends AbstractToTsFunction
{
    protected function getFunctionName(): string
    {
        return 'TO_TSVECTOR';
    }
}
