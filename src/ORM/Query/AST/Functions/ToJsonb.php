<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;

/**
 * Implementation of PostgreSql TO_JSONB() function.
 *
 * @see https://www.postgresql.org/docs/current/functions-json.html#FUNCTIONS-JSON-CREATION-TABLE
 *
 * @example TO_JSONB(entity.field)
 * @example TO_JSONB('[1,2,3]')
 * @example TO_JSONB('{"a": 2}')
 */
class ToJsonb extends AbstractSingleNodeFunction
{
    protected function getFunctionName(): string
    {
        return 'TO_JSONB';
    }

    protected function parseField(Parser $parser): Node
    {
        return $parser->StringPrimary();
    }
}
