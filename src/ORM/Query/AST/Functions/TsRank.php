<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql TS_RANK() function.
 *
 * @see https://www.postgresql.org/docs/current/textsearch-controls.html#TEXTSEARCH-RANKING
 *
 * @example TS_RANK(entity.field, TO_TSQUERY('text'))
 * @example TS_RANK(entity.field, TO_TSQUERY('text'), 32)
 * @example TS_RANK(TO_TSVECTOR('some text'), TO_TSQUERY('text'), 32)
 */
class TsRank extends FunctionNode
{
    protected Node $vector;

    protected Node $query;

    protected ?Node $normalization = null;

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->vector = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->query = $parser->StringPrimary();

        if ($parser->getLexer()->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->normalization = $parser->ArithmeticPrimary();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf('TS_RANK(%s, %s%s)',
            $this->vector->dispatch($sqlWalker),
            $this->query->dispatch($sqlWalker),
            $this->normalization !== null ? ', ' . $this->normalization->dispatch($sqlWalker) : ''
        );
    }
}
