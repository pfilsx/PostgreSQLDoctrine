<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Pfilsx\PostgreSQLDoctrine\Enum\TokenType;

/**
 * Implementation of PostgreSql JSON(B) object field retrieval by path.
 *
 * @see https://www.postgresql.org/docs/current/functions-json.html
 *
 * @example JSON_GET_FIELD(entity.field, 'a', 'b')
 */
class JsonGetField extends FunctionNode
{
    protected Node $field;

    /**
     * @var Node[]
     */
    protected array $path = [];

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        $this->field = $parser->StringPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->path[] = $parser->StringPrimary();

        if (!$parser->getLexer()->isNextToken(TokenType::T_CLOSE_PARENTHESIS)) {
            while ($parser->getLexer()->isNextToken(TokenType::T_COMMA)) {
                $parser->match(TokenType::T_COMMA);
                $this->path[] = $parser->StringPrimary();
            }
        }

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return \sprintf(
            '%s->%s',
            $this->field->dispatch($sqlWalker),
            \implode(
                '->',
                \array_map(static fn (Node $node) => $node->dispatch($sqlWalker), $this->path)
            )
        );
    }
}
