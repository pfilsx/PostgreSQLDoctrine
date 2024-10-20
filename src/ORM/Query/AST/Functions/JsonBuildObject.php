<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\AST\TypedExpression;

/**
 * Implementation of PostgreSql JSON_BUILD_OBJECT() function.
 *
 * @see https://www.postgresql.org/docs/current/functions-json.html#FUNCTIONS-JSON-CREATION-TABLE
 *
 * @example JSON_BUILD_OBJECT('key1', entity.field, 'key2', entity.field2)
 * @example JSON_BUILD_OBJECT(entity.field, entity.field2)
 */
class JsonBuildObject extends AbstractJsonBuildFunction implements TypedExpression
{
    protected function getFunctionName(): string
    {
        return 'JSON_BUILD_OBJECT';
    }

    public function getReturnType(): Type
    {
        return Type::getType(Types::JSON);
    }
}
