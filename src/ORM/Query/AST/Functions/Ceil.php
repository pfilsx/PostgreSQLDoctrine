<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Pfilsx\PostgreSQLDoctrine\Enum\TokenType;

/**
 * Implementation of PostgreSql CEIL() function.
 *
 * @see https://www.postgresql.org/docs/current/functions-math.html#FUNCTIONS-MATH-FUNC-TABLE
 *
 * @example CEIL(entity.field)
 */
final class Ceil extends FunctionNode
{
    private Node $field;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        $this->field = $parser->ArithmeticPrimary();

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return "CEIL({$this->field->dispatch($sqlWalker)})";
    }
}
