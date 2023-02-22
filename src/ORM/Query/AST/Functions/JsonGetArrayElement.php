<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;

/**
 * Implementation of PostgreSql json array field retrieval by index
 * @see https://www.postgresql.org/docs/current/functions-json.html
 * @example JSON_GET_ARRAY_ELEMENT(entity.field, 1)
 */
final class JsonGetArrayElement extends JsonGetField
{
    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->field = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->path[] = $parser->ArithmeticPrimary();
        if (!$parser->getLexer()->isNextToken(Lexer::T_CLOSE_PARENTHESIS)) {
            while ($parser->getLexer()->isNextToken(Lexer::T_COMMA)) {
                $parser->match(Lexer::T_COMMA);
                $this->path[] = $parser->ArithmeticPrimary();
            }
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}