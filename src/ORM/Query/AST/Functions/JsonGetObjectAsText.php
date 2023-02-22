<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql json object as text retrieval by path
 * @see https://www.postgresql.org/docs/current/functions-json.html
 * @example JSON_GET_OBJECT_AS_TEXT(entity.field, '{a,b}')
 */
final class JsonGetObjectAsText extends JsonGetObject
{
    public function getSql(SqlWalker $sqlWalker): string
    {
        return "{$this->field->dispatch($sqlWalker)} #>> {$this->path->dispatch($sqlWalker)}";
    }
}