<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;

/**
 * Implementation of PostgreSql JSON_EACH() function.
 *
 * @see https://www.postgresql.org/docs/current/functions-json.html#FUNCTIONS-JSON-PROCESSING-TABLE
 *
 * @example JSON_EACH(entity.field)
 */
class JsonEach extends AbstractSingleNodeFunction
{
    protected function getFunctionName(): string
    {
        return 'JSON_EACH';
    }

    protected function parseField(Parser $parser): Node
    {
        return $parser->StringPrimary();
    }
}
