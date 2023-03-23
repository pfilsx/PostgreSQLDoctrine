<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\ArrayExpression;

/**
 * Implementation of PostgreSql ARRAY[] type.
 *
 * @see https://www.postgresql.org/docs/current/arrays.html
 *
 * @example ARRAY(1,2,3)
 * @example ARRAY('test')
 * @example ARRAY(:input)
 */
class ToArray extends AbstractSingleNodeFunction
{
    protected function getFunctionName(): string
    {
        return 'ARRAY';
    }

    protected function parseField(Parser $parser): Node
    {
        return ArrayExpression::parse($parser);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return "ARRAY[{$this->field->dispatch($sqlWalker)}]";
    }
}
