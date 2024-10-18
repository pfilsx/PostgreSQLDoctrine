<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql EXTRACT function.
 *
 * @see https://www.postgresql.org/docs/current/functions-datetime.html#FUNCTIONS-DATETIME-EXTRACT
 *
 * @example EXTRACT(EPOCH FROM entity.field)
 * @example EXTRACT(DAY FROM entity2.field)
 */
class Extract extends FunctionNode
{
    private string $expr;

    private Node $from;

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $parser->match(Lexer::T_IDENTIFIER);
        $this->expr = $parser->getLexer()->token['value'];
        $parser->match(Lexer::T_FROM);

        $this->from = $parser->StringPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return \sprintf('EXTRACT(%s FROM %s)', $this->expr, $this->from->dispatch($sqlWalker));
    }
}
