<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;

/**
 * Implementation of PostgreSql JSON_OBJECT_KEYS() function.
 *
 * @see https://www.postgresql.org/docs/current/functions-json.html#FUNCTIONS-JSON-PROCESSING-TABLE
 *
 * @example JSON_OBJECT_KEYS(entity.field)
 */
class JsonbObjectKeys extends AbstractSingleNodeFunction
{
    protected function getFunctionName(): string
    {
        return 'JSON_OBJECT_KEYS';
    }

    protected function parseField(Parser $parser): Node
    {
        return $parser->StringPrimary();
    }
}
