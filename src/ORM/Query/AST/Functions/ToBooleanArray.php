<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql ARRAY[] type for booleans.
 *
 * @see https://www.postgresql.org/docs/current/arrays.html
 *
 * @example BOOLEAN_ARRAY(1, 0, 1)
 * @example BOOLEAN_ARRAY(:input)
 */
class ToBooleanArray extends ToArray
{
    public function getSql(SqlWalker $sqlWalker): string
    {
        return parent::getSql($sqlWalker) . '::boolean[]';
    }
}
