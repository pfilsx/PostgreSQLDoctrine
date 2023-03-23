<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;

/**
 * Implementation of PostgreSql ARRAY_TO_JSON() function.
 *
 * @see https://www.postgresql.org/docs/current/functions-json.html#FUNCTIONS-JSON-CREATION-TABLE
 *
 * @example ARRAY_TO_JSON(entity.field)
 * @example ARRAY_TO_JSON(ARRAY(1,2,3))
 */
class ArrayToJson extends AbstractSingleNodeFunction
{
    protected function getFunctionName(): string
    {
        return 'ARRAY_TO_JSON';
    }

    protected function parseField(Parser $parser): Node
    {
        return $parser->StringPrimary();
    }
}
