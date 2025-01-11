<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ToTsVector;

/**
 * @see ToTsVector
 */
final class ToTsVectorTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ['TO_TSVECTOR()', false],
            ['TO_TSVECTOR(e.field)', true],
            ['TO_TSVECTOR(:text)', true],
            ["TO_TSVECTOR('some text')", true],
            ["TO_TSVECTOR('english', :text)", true],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ['TO_TSVECTOR(e.field)', 'TO_TSVECTOR(e.field)'],
            ['TO_TSVECTOR(:text)', 'TO_TSVECTOR(?)'],
            ["TO_TSVECTOR('some text')", "TO_TSVECTOR('some text')"],
            ["TO_TSVECTOR('english', :text)", "TO_TSVECTOR('english', ?)"],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new ToTsVector('TO_TSVECTOR');
    }
}
