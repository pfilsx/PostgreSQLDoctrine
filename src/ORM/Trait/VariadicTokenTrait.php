<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Trait;

use Doctrine\Common\Lexer\Token;

/**
 * @internal
 */
trait VariadicTokenTrait
{
    protected function getTokenField($token, string $field): mixed
    {
        if ($token === null) {
            return null;
        }

        if (\class_exists(Token::class) && $token instanceof Token) {
            return $token->$field;
        }

        return $token[$field] ?? null;
    }
}
