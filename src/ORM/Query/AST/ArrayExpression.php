<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST;

use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;

final class ArrayExpression extends Node
{
    /**
     * @var Node[]
     */
    private array $innerNodes;

    /**
     * @param Node[] $innerNodes
     */
    public function __construct(array $innerNodes)
    {
        $this->innerNodes = $innerNodes;
    }

    public function dispatch($walker): string
    {
        return implode(', ', array_map(static fn (Node $node) => $node->dispatch($walker), $this->innerNodes));
    }

    public static function parse(Parser $parser): self
    {
        $lexer = $parser->getLexer();
        assert($lexer->lookahead !== null);
        $nodes = [];

        switch ($lexer->lookahead['type']) {
            case Lexer::T_INPUT_PARAMETER:
                $nodes[] = $parser->InputParameter();

                break;
            case Lexer::T_INTEGER:
            case Lexer::T_FLOAT:
            case Lexer::T_TRUE:
            case Lexer::T_FALSE:
            case Lexer::T_STRING:
                $nodes[] = $parser->Literal();
                while ($lexer->isNextToken(Lexer::T_COMMA)) {
                    $parser->match(Lexer::T_COMMA);
                    $nodes[] = $parser->Literal();
                }

                break;
        }

        return new self($nodes);
    }
}
