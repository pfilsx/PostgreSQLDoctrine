<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Literal;
use Doctrine\ORM\Query\Parser;
use Pfilsx\PostgreSQLDoctrine\Enum\TokenType;
use Pfilsx\PostgreSQLDoctrine\ORM\Trait\VariadicTokenTrait;

/**
 * Implementation of PostgreSql JSON(B) array field retrieval by index.
 *
 * @see https://www.postgresql.org/docs/current/functions-json.html
 *
 * @example JSON_GET_ARRAY_ELEMENT_AS_TEXT(entity.field, 1)
 */
class JsonGetArrayElementAsText extends JsonGetFieldAsText
{
    use VariadicTokenTrait;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        $this->field = $parser->StringPrimary();
        $parser->match(TokenType::T_COMMA);

        $parser->match(TokenType::T_INTEGER);
        $this->path[] = new Literal(Literal::NUMERIC, $this->getTokenField($parser->getLexer()->token, 'value'));

        if (!$parser->getLexer()->isNextToken(TokenType::T_CLOSE_PARENTHESIS)) {
            while ($parser->getLexer()->isNextToken(TokenType::T_COMMA)) {
                $parser->match(TokenType::T_COMMA);

                $parser->match(TokenType::T_INTEGER);
                $this->path[] = new Literal(Literal::NUMERIC, $this->getTokenField($parser->getLexer()->token, 'value'));
            }
        }

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }
}
