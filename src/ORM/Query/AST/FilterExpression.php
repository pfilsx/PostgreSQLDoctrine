<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Query\AST;

use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\AST\WhereClause;

final class FilterExpression extends Node
{
    private WhereClause $whereClause;

    public function __construct(WhereClause $whereClause)
    {
        $this->whereClause = $whereClause;
    }

    public function dispatch($walker): string
    {
        return "FILTER ({$this->whereClause->dispatch($walker)})";
    }
}
