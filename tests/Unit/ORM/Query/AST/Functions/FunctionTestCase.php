<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\AST\AggregateExpression;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use PHPUnit\Framework\TestCase;

abstract class FunctionTestCase extends TestCase
{
    /**
     * @dataProvider providerTestParse
     */
    public function testParse(string $dql, bool $valid): void
    {
        $parser = $this->prepareParser($dql);
        $function = $this->getFunction();

        if (!$valid) {
            self::expectException(QueryException::class);
        }

        $function->parse($parser);
        self::assertEquals(1, 1);
    }

    abstract public static function providerTestParse(): array;

    /**
     * @dataProvider providerTestGetSql
     */
    public function testGetSql(string $dql, string $sql): void
    {
        $parser = $this->prepareParser($dql);
        $function = $this->getFunction();
        $function->parse($parser);

        self::assertSame($sql, $function->getSql($this->createSqlWalkerMock()));
    }

    abstract public static function providerTestGetSql(): array;

    abstract protected function getFunction(): Query\AST\Functions\FunctionNode;

    protected function createEntityManagerMock()
    {
        $em = $this->createMock(EntityManager::class);
        $em->method('getConfiguration')->willReturn($this->createMock(Configuration::class));

        return $em;
    }

    protected function createSqlWalkerMock()
    {
        $mock = $this->createMock(Query\SqlWalker::class);
        $mock->method('walkLiteral')
            ->willReturnCallback(
                static function (Query\AST\Literal $literal) {
                    return match ($literal->type) {
                        Query\AST\Literal::STRING => "'$literal->value'",
                        Query\AST\Literal::BOOLEAN => $literal->value ? 'true' : 'false',
                        Query\AST\Literal::NUMERIC => (string) $literal->value,
                    };
                }
            );

        $mock->method('walkPathExpression')
            ->willReturnCallback(static fn (Query\AST\PathExpression $expr) => "$expr->identificationVariable.$expr->field");

        $mock->method('walkInputParameter')->willReturn('?');

        $mock->method('walkWhereClause')->willReturn('{WHERE}');

        $mock->method('walkAggregateExpression')->willReturnCallback(static function (AggregateExpression $expr) {
            return $expr->functionName . '(' . ($expr->isDistinct ? 'DISTINCT ' : '')
                . ($expr->pathExpression instanceof Query\AST\PathExpression
                    ? "{$expr->pathExpression->identificationVariable}.{$expr->pathExpression->field})"
                    : $expr->pathExpression
                );
        });

        return $mock;
    }

    protected function prepareParser(string $dql): Parser
    {
        $query = new Query($this->createEntityManagerMock());
        $query->setDQL($dql);
        $parser = new Parser($query);
        $parser->getLexer()->moveNext();

        return $parser;
    }
}
