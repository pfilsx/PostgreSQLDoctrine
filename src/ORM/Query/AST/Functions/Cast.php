<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Pfilsx\PostgreSQLDoctrine\Enum\TokenType;
use Pfilsx\PostgreSQLDoctrine\ORM\Trait\VariadicTokenTrait;

/**
 * Implementation of PostgreSql CAST() function.
 *
 * @see https://www.postgresql.org/docs/current/sql-createcast.html
 *
 * @example CAST(entity.field AS text)
 */
final class Cast extends FunctionNode
{
    use VariadicTokenTrait;

    public Node $source;

    public string $type;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->source = $parser->SimpleArithmeticExpression();

        $parser->match(TokenType::T_AS);
        $parser->match(TokenType::T_IDENTIFIER);

        $type = $this->getTokenField($parser->getLexer()->token, 'value');

        if (!\is_string($type)) {
            return;
        }

        $this->type = $type;

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return \sprintf('CAST(%s AS %s)', $this->source->dispatch($sqlWalker), $this->type);
    }
}
