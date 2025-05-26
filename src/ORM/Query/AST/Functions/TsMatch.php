<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Pfilsx\PostgreSQLDoctrine\Enum\TokenType;

/**
 * Implementation of PostgreSql text search matching operator ( @@ ).
 *
 * @see https://www.postgresql.org/docs/current/textsearch-intro.html#TEXTSEARCH-MATCHING
 *
 * @example TS_MATCH(entity.field, TO_TSQUERY('query text'))
 */
class TsMatch extends FunctionNode
{
    private Node $vector;

    private Node $query;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->vector = $parser->StringPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->query = $parser->StringPrimary();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return "({$this->vector->dispatch($sqlWalker)} @@ {$this->query->dispatch($sqlWalker)})";
    }
}
