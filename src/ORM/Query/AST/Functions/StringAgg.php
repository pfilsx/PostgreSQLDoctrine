<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql STRING_AGG() function.
 *
 * @see https://www.postgresql.org/docs/current/functions-aggregate.html#FUNCTIONS-AGGREGATE-TABLE
 *
 * @example STRING_AGG(entity.field, ', ')
 * @example STRING_AGG(entity.field, ', ') FILTER (WHERE entity.field IS NOT NULL)
 */
final class StringAgg extends AbstractAggregateWithFilterFunction
{
    private bool $distinct = false;
    private Node $expr;
    private Node $delimiter;

    public function parseFunction(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $lexer = $parser->getLexer();
        if ($lexer->isNextToken(Lexer::T_DISTINCT)) {
            $parser->match(Lexer::T_DISTINCT);
            $this->distinct = true;
        }

        $this->expr = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->delimiter = $parser->StringPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getFunctionSql(SqlWalker $sqlWalker): string
    {
        return sprintf('STRING_AGG(%s%s, %s)',
            $this->distinct ? 'DISTINCT ' : '',
            $this->expr->dispatch($sqlWalker),
            $this->delimiter->dispatch($sqlWalker)
        );
    }
}
