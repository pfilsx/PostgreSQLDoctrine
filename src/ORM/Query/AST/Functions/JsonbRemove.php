<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;


/**
 * Implementation of PostgreSql jsonb remove by key function
 * @see https://www.postgresql.org/docs/current/functions-json.html#FUNCTIONS-JSONB-OP-TABLE
 * @example JSONB_REMOVE(entity.field, 'a')
 */
final class JsonbRemove extends FunctionNode
{
    protected Node $jsonb;

    protected Node $key;
    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->jsonb = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->key = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }


    public function getSql(SqlWalker $sqlWalker): string
    {
        return "{$this->jsonb->dispatch($sqlWalker)} - {$this->key->dispatch($sqlWalker)}";
    }
}