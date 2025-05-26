<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST;

use Doctrine\Common\Lexer\Token;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Pfilsx\PostgreSQLDoctrine\Enum\TokenType;

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
        return \implode(', ', \array_map(static fn (Node $node) => $node->dispatch($walker), $this->innerNodes));
    }

    public static function parse(Parser $parser): self
    {
        $lexer = $parser->getLexer();
        \assert($lexer->lookahead !== null);
        $nodes = [];

        if (\class_exists(Token::class) && $lexer->lookahead instanceof Token) {
            $type = $lexer->lookahead->type;
        } else {
            $type = $lexer->lookahead['type'] ?? null;
        }

        switch ($type) {
            case TokenType::T_INPUT_PARAMETER:
                $nodes[] = $parser->InputParameter();

                break;
            case TokenType::T_INTEGER:
            case TokenType::T_FLOAT:
            case TokenType::T_TRUE:
            case TokenType::T_FALSE:
            case TokenType::T_STRING:
                $nodes[] = $parser->Literal();
                while ($lexer->isNextToken(TokenType::T_COMMA)) {
                    $parser->match(TokenType::T_COMMA);
                    $nodes[] = $parser->Literal();
                }

                break;
        }

        return new self($nodes);
    }
}
