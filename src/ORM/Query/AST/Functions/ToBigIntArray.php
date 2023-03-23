<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql ARRAY[] type for bigint.
 *
 * @see https://www.postgresql.org/docs/current/arrays.html
 *
 * @example BIGINT_ARRAY('1', '2', '3')
 * @example BIGINT_ARRAY(:input)
 */
class ToBigIntArray extends ToArray
{
    public function getSql(SqlWalker $sqlWalker): string
    {
        return parent::getSql($sqlWalker) . '::bigint[]';
    }
}
