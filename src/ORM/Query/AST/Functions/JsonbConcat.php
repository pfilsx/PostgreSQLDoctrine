<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql jsonb concatenation function.
 *
 * @see https://www.postgresql.org/docs/current/functions-json.html#FUNCTIONS-JSONB-OP-TABLE
 *
 * @example JSONB_CONCAT(entity.field, entity2.field)
 */
final class JsonbConcat extends FunctionNode
{
    protected Node $json1;

    protected Node $json2;

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->json1 = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->json2 = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return "{$this->json1->dispatch($sqlWalker)} || {$this->json2->dispatch($sqlWalker)}";
    }
}
