<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql ARRAY[] type for smallint.
 *
 * @see https://www.postgresql.org/docs/current/arrays.html
 *
 * @example SMALLINT_ARRAY('1', '2', '3')
 * @example SMALLINT_ARRAY(:input)
 */
class ToSmallIntArray extends ToArray
{
    public function getSql(SqlWalker $sqlWalker): string
    {
        return parent::getSql($sqlWalker) . '::smallint[]';
    }
}
