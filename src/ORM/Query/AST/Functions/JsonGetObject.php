<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql JSON(B) object retrieval by path.
 *
 * @see https://www.postgresql.org/docs/current/functions-json.html
 *
 * @example JSON_GET_OBJECT(entity.field, '{a,b}')
 */
class JsonGetObject extends FunctionNode
{
    protected Node $field;

    protected Node $path;

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->field = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->path = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return "{$this->field->dispatch($sqlWalker)} #> {$this->path->dispatch($sqlWalker)}";
    }
}
