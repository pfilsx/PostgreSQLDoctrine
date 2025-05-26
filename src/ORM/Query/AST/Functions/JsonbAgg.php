<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Pfilsx\PostgreSQLDoctrine\Enum\TokenType;

/**
 * Implementation of PostgreSql JSONB_AGG() function.
 *
 * @see https://www.postgresql.org/docs/current/functions-aggregate.html#FUNCTIONS-AGGREGATE-TABLE
 *
 * @example JSONB_AGG(entity.field)
 * @example JSONB_AGG(entity.field) FILTER (WHERE entity.field IS NOT NULL)
 */
final class JsonbAgg extends AbstractAggregateWithFilterFunction
{
    private bool $distinct = false;

    private Node $expr;

    public function parseFunction(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        $lexer = $parser->getLexer();
        if ($lexer->isNextToken(TokenType::T_DISTINCT)) {
            $parser->match(TokenType::T_DISTINCT);
            $this->distinct = true;
        }

        $this->expr = $parser->StringPrimary();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getFunctionSql(SqlWalker $sqlWalker): string
    {
        return \sprintf('JSONB_AGG(%s%s)', $this->distinct ? 'DISTINCT ' : '', $this->expr->dispatch($sqlWalker));
    }
}
