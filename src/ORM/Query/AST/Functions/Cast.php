<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql CAST() function.
 *
 * @see https://www.postgresql.org/docs/current/sql-createcast.html
 *
 * @example CAST(entity.field AS text)
 */
final class Cast extends FunctionNode
{
    public Node $source;

    public string $type;

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->source = $parser->SimpleArithmeticExpression();

        $parser->match(Lexer::T_AS);
        $parser->match(Lexer::T_IDENTIFIER);

        $type = $parser->getLexer()->token['value'] ?? null;

        if (!is_string($type)) {
            return;
        }

        $this->type = $type;

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return \sprintf('CAST(%s AS %s)', $this->source->dispatch($sqlWalker), $this->type);
    }
}
