<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql TS_HEADLINE() function.
 *
 * @see https://www.postgresql.org/docs/current/textsearch-controls.html#TEXTSEARCH-HEADLINE
 *
 * @example TS_HEADLINE(entity.field, TO_TSQUERY('text'))
 * @example TS_HEADLINE('some text', TO_TSQUERY('text'))
 * @example TS_HEADLINE('english', entity.field, TO_TSQUERY('text'))
 * @example TS_HEADLINE('english', entity.field, TO_TSQUERY('text'), 'MaxWords=7, MinWords=3')
 */
class TsHeadline extends FunctionNode
{
    private Node $node1;
    private Node $node2;
    private ?Node $node3 = null;
    private ?Node $node4 = null;

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->node1 = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->node2 = $parser->StringPrimary();

        if ($parser->getLexer()->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->node3 = $parser->StringPrimary();
        }

        if ($parser->getLexer()->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->node4 = $parser->StringPrimary();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf('TS_HEADLINE(%s, %s%s%s)',
            $this->node1->dispatch($sqlWalker),
            $this->node2->dispatch($sqlWalker),
            $this->node3 !== null ? ', ' . $this->node3->dispatch($sqlWalker) : '',
            $this->node4 !== null ? ', ' . $this->node4->dispatch($sqlWalker) : ''
        );
    }
}
