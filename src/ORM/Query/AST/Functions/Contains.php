<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\InputParameter;
use Doctrine\ORM\Query\AST\Literal;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql contains operator (||).
 *
 * @see https://www.postgresql.org/docs/current/functions-json.html#FUNCTIONS-JSONB-OP-TABLE
 * @see https://www.postgresql.org/docs/current/functions-array.html
 *
 * @example CONTAINS(entity.field, entity2.field)
 * @example CONTAINS(entity.field, TO_JSON('{"a": 1}'))
 * @example CONTAINS(entity.field, TO_JSONB('{"a": 1}'))
 * @example CONTAINS(entity.field, ARRAY(1, 2, 3))
 */
final class Contains extends FunctionNode
{
    protected Node $field;

    protected Node $value;

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->field = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->value = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        $value = $this->value->dispatch($sqlWalker);
        if ($this->value instanceof Literal || $this->value instanceof InputParameter) {
            $value .= '::jsonb';
        }

        return "({$this->field->dispatch($sqlWalker)} @> $value)";
    }
}
