<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql json object field as text retrieval by path.
 *
 * @see https://www.postgresql.org/docs/current/functions-json.html
 *
 * @example JSON_GET_FIELD_AS_TEXT(entity.field, 'a', 'b')
 */
class JsonGetFieldAsText extends JsonGetField
{
    public function getSql(SqlWalker $sqlWalker): string
    {
        $path = array_map(static fn (Node $node) => $node->dispatch($sqlWalker), $this->path);

        $lastPathElement = array_pop($path);

        $sql = $this->field->dispatch($sqlWalker);

        if (count($path) > 0) {
            $sql .= sprintf('->%s', implode('->', $path));
        }

        return "$sql->>$lastPathElement";
    }
}
