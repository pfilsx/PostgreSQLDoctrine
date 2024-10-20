<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql ILIKE() function.
 *
 * @see https://www.postgresql.org/docs/current/functions-matching.html#FUNCTIONS-LIKE
 *
 * @example ILIKE(entity.field, 'text')
 * @example ILIKE(entity.field, entity2.field)
 * @example ILIKE(entity.field, ANY ARRAY(:texts))
 */
class ILike extends FunctionNode
{
    private Node $field;

    private Node $text;

    private bool $multiple = false;

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->field = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);

        $lexer = $parser->getLexer();

        if ($lexer->isNextToken(Lexer::T_ANY)) {
            $parser->match(Lexer::T_ANY);
            $this->multiple = true;
        }

        $this->text = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return \sprintf(
            '(%s ILIKE %s)',
            $this->field->dispatch($sqlWalker),
            $this->multiple ? "ANY ({$this->text->dispatch($sqlWalker)})" : $this->text->dispatch($sqlWalker),
        );
    }
}
