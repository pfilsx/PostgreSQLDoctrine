<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\FilterExpression;

abstract class AggregateWithFilterFunction extends FunctionNode
{
    private const FILTER_IDENTIFIER = 'FILTER';
    private ?FilterExpression $filterExpression = null;

    public function parse(Parser $parser): void
    {
        $this->parseFunction($parser);

        $lexer = $parser->getLexer();

        if (!$lexer->isNextToken(Lexer::T_IDENTIFIER)) {
            return;
        }

        $lookaheadValue = $lexer->lookahead['value'] ?? null;

        if (!is_string($lookaheadValue) || mb_strtoupper($lookaheadValue) !== self::FILTER_IDENTIFIER) {
            return;
        }

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->filterExpression = new FilterExpression($parser->WhereClause());

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    abstract public function parseFunction(Parser $parser): void;

    public function getSql(SqlWalker $sqlWalker): string
    {
        $sql = $this->getFunctionSql($sqlWalker);

        if ($this->filterExpression !== null) {
            $sql .= " {$this->filterExpression->dispatch($sqlWalker)}";
        }

        return $sql;
    }

    abstract public function getFunctionSql(SqlWalker $sqlWalker): string;
}
