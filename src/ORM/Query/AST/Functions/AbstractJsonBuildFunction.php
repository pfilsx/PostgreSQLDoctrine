<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Pfilsx\PostgreSQLDoctrine\Enum\TokenType;

abstract class AbstractJsonBuildFunction extends FunctionNode
{
    /**
     * @var Node[]
     */
    protected array $elements = [];

    abstract protected function getFunctionName(): string;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->elements[] = $parser->StringPrimary();

        if (!$parser->getLexer()->isNextToken(TokenType::T_CLOSE_PARENTHESIS)) {
            while ($parser->getLexer()->isNextToken(TokenType::T_COMMA)) {
                $parser->match(TokenType::T_COMMA);
                $this->elements[] = $parser->StringPrimary();
            }
        }

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return \sprintf(
            '%s(%s)',
            $this->getFunctionName(),
            \implode(',', \array_map(static fn (Node $node) => $node->dispatch($sqlWalker), $this->elements))
        );
    }
}
