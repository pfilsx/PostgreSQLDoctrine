<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

abstract class AbstractToTsFunction extends FunctionNode
{
    private Node $document;

    private ?Node $config = null;

    abstract protected function getFunctionName(): string;

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $firstNode = $parser->StringPrimary();

        $lexer = $parser->getLexer();

        if ($lexer->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->config = $firstNode;
            $this->document = $parser->StringPrimary();
        } else {
            $this->document = $firstNode;
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf('%s(%s%s)',
            $this->getFunctionName(),
            $this->config !== null ? $this->config->dispatch($sqlWalker) . ', ' : '',
            $this->document->dispatch($sqlWalker)
        );
    }
}
