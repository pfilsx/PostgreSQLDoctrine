<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Pfilsx\PostgreSQLDoctrine\Enum\TokenType;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\FilterExpression;
use Pfilsx\PostgreSQLDoctrine\ORM\Trait\VariadicTokenTrait;

abstract class AbstractAggregateWithFilterFunction extends FunctionNode
{
    use VariadicTokenTrait;

    private const FILTER_IDENTIFIER = 'FILTER';
    private ?FilterExpression $filterExpression = null;

    public function parse(Parser $parser): void
    {
        $this->parseFunction($parser);

        $lexer = $parser->getLexer();

        if (!$lexer->isNextToken(TokenType::T_IDENTIFIER)) {
            return;
        }

        $lookaheadValue = $this->getTokenField($lexer->lookahead, 'value');

        if (!\is_string($lookaheadValue) || \mb_strtoupper($lookaheadValue) !== self::FILTER_IDENTIFIER) {
            return;
        }

        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        $this->filterExpression = new FilterExpression($parser->WhereClause());

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
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
