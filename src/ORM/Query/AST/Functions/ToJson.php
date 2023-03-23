<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;

/**
 * Implementation of PostgreSql TO_JSON() function.
 *
 * @see https://www.postgresql.org/docs/current/functions-json.html#FUNCTIONS-JSON-CREATION-TABLE
 *
 * @example TO_JSON(entity.field)
 * @example TO_JSON('[1,2,3]')
 * @example TO_JSON('{"a": 2}')
 */
class ToJson extends AbstractSingleNodeFunction
{
    protected function getFunctionName(): string
    {
        return 'TO_JSON';
    }

    protected function parseField(Parser $parser): Node
    {
        return $parser->StringPrimary();
    }
}
