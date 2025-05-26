<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Enum;

if (\class_exists(\Doctrine\ORM\Query\TokenType::class)) {
    final class TokenType
    {
        public const T_NONE = \Doctrine\ORM\Query\TokenType::T_NONE;

        public const T_INTEGER = \Doctrine\ORM\Query\TokenType::T_INTEGER;

        public const T_STRING = \Doctrine\ORM\Query\TokenType::T_STRING;

        public const T_INPUT_PARAMETER = \Doctrine\ORM\Query\TokenType::T_INPUT_PARAMETER;

        public const T_FLOAT = \Doctrine\ORM\Query\TokenType::T_FLOAT;

        public const T_CLOSE_PARENTHESIS = \Doctrine\ORM\Query\TokenType::T_CLOSE_PARENTHESIS;

        public const T_OPEN_PARENTHESIS = \Doctrine\ORM\Query\TokenType::T_OPEN_PARENTHESIS;

        public const T_COMMA = \Doctrine\ORM\Query\TokenType::T_COMMA;

        public const T_DIVIDE = \Doctrine\ORM\Query\TokenType::T_DIVIDE;

        public const T_DOT = \Doctrine\ORM\Query\TokenType::T_DOT;

        public const T_EQUALS = \Doctrine\ORM\Query\TokenType::T_EQUALS;

        public const T_GREATER_THAN = \Doctrine\ORM\Query\TokenType::T_GREATER_THAN;

        public const T_LOWER_THAN = \Doctrine\ORM\Query\TokenType::T_LOWER_THAN;

        public const T_MINUS = \Doctrine\ORM\Query\TokenType::T_MINUS;

        public const T_MULTIPLY = \Doctrine\ORM\Query\TokenType::T_MULTIPLY;

        public const T_NEGATE = \Doctrine\ORM\Query\TokenType::T_NEGATE;

        public const T_PLUS = \Doctrine\ORM\Query\TokenType::T_PLUS;

        public const T_OPEN_CURLY_BRACE = \Doctrine\ORM\Query\TokenType::T_OPEN_CURLY_BRACE;

        public const T_CLOSE_CURLY_BRACE = \Doctrine\ORM\Query\TokenType::T_CLOSE_CURLY_BRACE;

        public const T_ALIASED_NAME = \Doctrine\ORM\Query\TokenType::T_ALIASED_NAME;

        public const T_FULLY_QUALIFIED_NAME = \Doctrine\ORM\Query\TokenType::T_FULLY_QUALIFIED_NAME;

        public const T_IDENTIFIER = \Doctrine\ORM\Query\TokenType::T_IDENTIFIER;

        public const T_ALL = \Doctrine\ORM\Query\TokenType::T_ALL;

        public const T_AND = \Doctrine\ORM\Query\TokenType::T_AND;

        public const T_ANY = \Doctrine\ORM\Query\TokenType::T_ANY;

        public const T_AS = \Doctrine\ORM\Query\TokenType::T_AS;

        public const T_ASC = \Doctrine\ORM\Query\TokenType::T_ASC;

        public const T_AVG = \Doctrine\ORM\Query\TokenType::T_AVG;

        public const T_BETWEEN = \Doctrine\ORM\Query\TokenType::T_BETWEEN;

        public const T_BOTH = \Doctrine\ORM\Query\TokenType::T_BOTH;

        public const T_BY = \Doctrine\ORM\Query\TokenType::T_BY;

        public const T_CASE = \Doctrine\ORM\Query\TokenType::T_CASE;

        public const T_COALESCE = \Doctrine\ORM\Query\TokenType::T_COALESCE;

        public const T_COUNT = \Doctrine\ORM\Query\TokenType::T_COUNT;

        public const T_DELETE = \Doctrine\ORM\Query\TokenType::T_DELETE;

        public const T_DESC = \Doctrine\ORM\Query\TokenType::T_DESC;

        public const T_DISTINCT = \Doctrine\ORM\Query\TokenType::T_DISTINCT;

        public const T_ELSE = \Doctrine\ORM\Query\TokenType::T_ELSE;

        public const T_EMPTY = \Doctrine\ORM\Query\TokenType::T_EMPTY;

        public const T_END = \Doctrine\ORM\Query\TokenType::T_END;

        public const T_ESCAPE = \Doctrine\ORM\Query\TokenType::T_ESCAPE;

        public const T_EXISTS = \Doctrine\ORM\Query\TokenType::T_EXISTS;

        public const T_FALSE = \Doctrine\ORM\Query\TokenType::T_FALSE;

        public const T_FROM = \Doctrine\ORM\Query\TokenType::T_FROM;

        public const T_GROUP = \Doctrine\ORM\Query\TokenType::T_GROUP;

        public const T_HAVING = \Doctrine\ORM\Query\TokenType::T_HAVING;

        public const T_HIDDEN = \Doctrine\ORM\Query\TokenType::T_HIDDEN;

        public const T_IN = \Doctrine\ORM\Query\TokenType::T_IN;

        public const T_INDEX = \Doctrine\ORM\Query\TokenType::T_INDEX;

        public const T_INNER = \Doctrine\ORM\Query\TokenType::T_INNER;

        public const T_INSTANCE = \Doctrine\ORM\Query\TokenType::T_INSTANCE;
        public const T_IS = \Doctrine\ORM\Query\TokenType::T_IS;
        public const T_JOIN = \Doctrine\ORM\Query\TokenType::T_JOIN;
        public const T_LEADING = \Doctrine\ORM\Query\TokenType::T_LEADING;
        public const T_LEFT = \Doctrine\ORM\Query\TokenType::T_LEFT;
        public const T_LIKE = \Doctrine\ORM\Query\TokenType::T_LIKE;
        public const T_MAX = \Doctrine\ORM\Query\TokenType::T_MAX;
        public const T_MEMBER = \Doctrine\ORM\Query\TokenType::T_MEMBER;
        public const T_MIN = \Doctrine\ORM\Query\TokenType::T_MIN;
        public const T_NEW = \Doctrine\ORM\Query\TokenType::T_NEW;
        public const T_NOT = \Doctrine\ORM\Query\TokenType::T_NOT;
        public const T_NULL = \Doctrine\ORM\Query\TokenType::T_NULL;
        public const T_NULLIF = \Doctrine\ORM\Query\TokenType::T_NULLIF;
        public const T_OF = \Doctrine\ORM\Query\TokenType::T_OF;
        public const T_OR = \Doctrine\ORM\Query\TokenType::T_OR;
        public const T_ORDER = \Doctrine\ORM\Query\TokenType::T_ORDER;
        public const T_OUTER = \Doctrine\ORM\Query\TokenType::T_OUTER;
        public const T_PARTIAL = \Doctrine\ORM\Query\TokenType::T_PARTIAL;
        public const T_SELECT = \Doctrine\ORM\Query\TokenType::T_SELECT;
        public const T_SET = \Doctrine\ORM\Query\TokenType::T_SET;
        public const T_SOME = \Doctrine\ORM\Query\TokenType::T_SOME;
        public const T_SUM = \Doctrine\ORM\Query\TokenType::T_SUM;
        public const T_THEN = \Doctrine\ORM\Query\TokenType::T_THEN;
        public const T_TRAILING = \Doctrine\ORM\Query\TokenType::T_TRAILING;
        public const T_TRUE = \Doctrine\ORM\Query\TokenType::T_TRUE;
        public const T_UPDATE = \Doctrine\ORM\Query\TokenType::T_UPDATE;
        public const T_WHEN = \Doctrine\ORM\Query\TokenType::T_WHEN;
        public const T_WHERE = \Doctrine\ORM\Query\TokenType::T_WHERE;
        public const T_WITH = \Doctrine\ORM\Query\TokenType::T_WITH;
    }
} else {
    final class TokenType
    {
        public const T_NONE = \Doctrine\ORM\Query\Lexer::T_NONE;

        public const T_INTEGER = \Doctrine\ORM\Query\Lexer::T_INTEGER;

        public const T_STRING = \Doctrine\ORM\Query\Lexer::T_STRING;

        public const T_INPUT_PARAMETER = \Doctrine\ORM\Query\Lexer::T_INPUT_PARAMETER;

        public const T_FLOAT = \Doctrine\ORM\Query\Lexer::T_FLOAT;

        public const T_CLOSE_PARENTHESIS = \Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS;

        public const T_OPEN_PARENTHESIS = \Doctrine\ORM\Query\Lexer::T_OPEN_PARENTHESIS;

        public const T_COMMA = \Doctrine\ORM\Query\Lexer::T_COMMA;

        public const T_DIVIDE = \Doctrine\ORM\Query\Lexer::T_DIVIDE;

        public const T_DOT = \Doctrine\ORM\Query\Lexer::T_DOT;

        public const T_EQUALS = \Doctrine\ORM\Query\Lexer::T_EQUALS;

        public const T_GREATER_THAN = \Doctrine\ORM\Query\Lexer::T_GREATER_THAN;

        public const T_LOWER_THAN = \Doctrine\ORM\Query\Lexer::T_LOWER_THAN;

        public const T_MINUS = \Doctrine\ORM\Query\Lexer::T_MINUS;

        public const T_MULTIPLY = \Doctrine\ORM\Query\Lexer::T_MULTIPLY;

        public const T_NEGATE = \Doctrine\ORM\Query\Lexer::T_NEGATE;

        public const T_PLUS = \Doctrine\ORM\Query\Lexer::T_PLUS;

        public const T_OPEN_CURLY_BRACE = \Doctrine\ORM\Query\Lexer::T_OPEN_CURLY_BRACE;

        public const T_CLOSE_CURLY_BRACE = \Doctrine\ORM\Query\Lexer::T_CLOSE_CURLY_BRACE;

        public const T_ALIASED_NAME = \Doctrine\ORM\Query\Lexer::T_ALIASED_NAME;

        public const T_FULLY_QUALIFIED_NAME = \Doctrine\ORM\Query\Lexer::T_FULLY_QUALIFIED_NAME;

        public const T_IDENTIFIER = \Doctrine\ORM\Query\Lexer::T_IDENTIFIER;

        public const T_ALL = \Doctrine\ORM\Query\Lexer::T_ALL;

        public const T_AND = \Doctrine\ORM\Query\Lexer::T_AND;

        public const T_ANY = \Doctrine\ORM\Query\Lexer::T_ANY;

        public const T_AS = \Doctrine\ORM\Query\Lexer::T_AS;

        public const T_ASC = \Doctrine\ORM\Query\Lexer::T_ASC;

        public const T_AVG = \Doctrine\ORM\Query\Lexer::T_AVG;

        public const T_BETWEEN = \Doctrine\ORM\Query\Lexer::T_BETWEEN;

        public const T_BOTH = \Doctrine\ORM\Query\Lexer::T_BOTH;

        public const T_BY = \Doctrine\ORM\Query\Lexer::T_BY;

        public const T_CASE = \Doctrine\ORM\Query\Lexer::T_CASE;

        public const T_COALESCE = \Doctrine\ORM\Query\Lexer::T_COALESCE;

        public const T_COUNT = \Doctrine\ORM\Query\Lexer::T_COUNT;

        public const T_DELETE = \Doctrine\ORM\Query\Lexer::T_DELETE;

        public const T_DESC = \Doctrine\ORM\Query\Lexer::T_DESC;

        public const T_DISTINCT = \Doctrine\ORM\Query\Lexer::T_DISTINCT;

        public const T_ELSE = \Doctrine\ORM\Query\Lexer::T_ELSE;

        public const T_EMPTY = \Doctrine\ORM\Query\Lexer::T_EMPTY;

        public const T_END = \Doctrine\ORM\Query\Lexer::T_END;

        public const T_ESCAPE = \Doctrine\ORM\Query\Lexer::T_ESCAPE;

        public const T_EXISTS = \Doctrine\ORM\Query\Lexer::T_EXISTS;

        public const T_FALSE = \Doctrine\ORM\Query\Lexer::T_FALSE;

        public const T_FROM = \Doctrine\ORM\Query\Lexer::T_FROM;

        public const T_GROUP = \Doctrine\ORM\Query\Lexer::T_GROUP;

        public const T_HAVING = \Doctrine\ORM\Query\Lexer::T_HAVING;

        public const T_HIDDEN = \Doctrine\ORM\Query\Lexer::T_HIDDEN;

        public const T_IN = \Doctrine\ORM\Query\Lexer::T_IN;

        public const T_INDEX = \Doctrine\ORM\Query\Lexer::T_INDEX;

        public const T_INNER = \Doctrine\ORM\Query\Lexer::T_INNER;

        public const T_INSTANCE = \Doctrine\ORM\Query\Lexer::T_INSTANCE;
        public const T_IS = \Doctrine\ORM\Query\Lexer::T_IS;
        public const T_JOIN = \Doctrine\ORM\Query\Lexer::T_JOIN;
        public const T_LEADING = \Doctrine\ORM\Query\Lexer::T_LEADING;
        public const T_LEFT = \Doctrine\ORM\Query\Lexer::T_LEFT;
        public const T_LIKE = \Doctrine\ORM\Query\Lexer::T_LIKE;
        public const T_MAX = \Doctrine\ORM\Query\Lexer::T_MAX;
        public const T_MEMBER = \Doctrine\ORM\Query\Lexer::T_MEMBER;
        public const T_MIN = \Doctrine\ORM\Query\Lexer::T_MIN;
        public const T_NEW = \Doctrine\ORM\Query\Lexer::T_NEW;
        public const T_NOT = \Doctrine\ORM\Query\Lexer::T_NOT;
        public const T_NULL = \Doctrine\ORM\Query\Lexer::T_NULL;
        public const T_NULLIF = \Doctrine\ORM\Query\Lexer::T_NULLIF;
        public const T_OF = \Doctrine\ORM\Query\Lexer::T_OF;
        public const T_OR = \Doctrine\ORM\Query\Lexer::T_OR;
        public const T_ORDER = \Doctrine\ORM\Query\Lexer::T_ORDER;
        public const T_OUTER = \Doctrine\ORM\Query\Lexer::T_OUTER;
        public const T_PARTIAL = \Doctrine\ORM\Query\Lexer::T_PARTIAL;
        public const T_SELECT = \Doctrine\ORM\Query\Lexer::T_SELECT;
        public const T_SET = \Doctrine\ORM\Query\Lexer::T_SET;
        public const T_SOME = \Doctrine\ORM\Query\Lexer::T_SOME;
        public const T_SUM = \Doctrine\ORM\Query\Lexer::T_SUM;
        public const T_THEN = \Doctrine\ORM\Query\Lexer::T_THEN;
        public const T_TRAILING = \Doctrine\ORM\Query\Lexer::T_TRAILING;
        public const T_TRUE = \Doctrine\ORM\Query\Lexer::T_TRUE;
        public const T_UPDATE = \Doctrine\ORM\Query\Lexer::T_UPDATE;
        public const T_WHEN = \Doctrine\ORM\Query\Lexer::T_WHEN;
        public const T_WHERE = \Doctrine\ORM\Query\Lexer::T_WHERE;
        public const T_WITH = \Doctrine\ORM\Query\Lexer::T_WITH;
    }
}
