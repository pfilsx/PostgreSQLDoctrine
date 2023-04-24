<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\AST\AggregateExpression;
use Doctrine\ORM\Query\AST\TypedExpression;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Implementation of PostgreSql COUNT() function with FILTER (WHERE) support.
 *
 * @see https://www.postgresql.org/docs/current/functions-aggregate.html#FUNCTIONS-AGGREGATE-TABLE
 *
 * @example COUNT(entity.field)
 * @example COUNT(DISTINCT entity.field)
 * @example COUNT(entity.field) FILTER (WHERE entity.field IS NOT NULL)
 * @example COUNT(DISTINCT entity.field) FILTER (WHERE entity.field IS NOT NULL)
 */
final class Count extends AbstractAggregateWithFilterFunction implements TypedExpression
{
    private AggregateExpression $aggregateExpression;

    public function parseFunction(Parser $parser): void
    {
        $this->aggregateExpression = $parser->AggregateExpression();
    }

    public function getFunctionSql(SqlWalker $sqlWalker): string
    {
        return $this->aggregateExpression->dispatch($sqlWalker);
    }

    public function getReturnType(): Type
    {
        return Type::getType(Types::INTEGER);
    }
}
