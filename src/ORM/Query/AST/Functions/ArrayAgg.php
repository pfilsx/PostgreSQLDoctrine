<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql ARRAY_AGG().
 *
 * @see https://www.postgresql.org/docs/current/functions-aggregate.html#FUNCTIONS-AGGREGATE-TABLE
 *
 * @example ARRAY_AGG(entity.field)
 * @example ARRAY_AGG(entity.field) FILTER (WHERE entity.field IS NOT NULL)
 */
final class ArrayAgg extends AggregateWithFilterFunction
{
    private Node $expr;

    public function parseFunction(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->expr = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getFunctionSql(SqlWalker $sqlWalker): string
    {
        return "ARRAY_AGG({$this->expr->dispatch($sqlWalker)})";
    }
}
