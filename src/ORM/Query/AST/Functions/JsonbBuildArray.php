<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

/**
 * Implementation of PostgreSql JSONB_BUILD_ARRAY() function.
 *
 * @see https://www.postgresql.org/docs/current/functions-json.html#FUNCTIONS-JSON-CREATION-TABLE
 *
 * @example JSONB_BUILD_ARRAY(entity.field, entity.field2, entity.field3)
 */
class JsonbBuildArray extends AbstractJsonBuildFunction
{
    protected function getFunctionName(): string
    {
        return 'JSONB_BUILD_ARRAY';
    }
}
