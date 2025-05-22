<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query;
use Pfilsx\PostgreSQLDoctrine\ORM\Query\AST\Functions\ILike;

/**
 * @see ILike
 */
final class ILikeTest extends FunctionTestCase
{
    public static function providerTestParse(): array
    {
        return [
            ["ILIKE(e.field, 'text')", true],
            ['ILIKE(e.field, :text)', true],
            ['ILIKE(e.field, ANY :texts)', true],
            ['ILIKE(e.field)', false],
            ['ILIKE e.field', false],
            ["ILIKE('text')", false],
        ];
    }

    public static function providerTestGetSql(): array
    {
        return [
            ["ILIKE(e.field, 'text')", '(e.field ILIKE \'text\')'],
            ['ILIKE(e.field, :text)', '(e.field ILIKE ?)'],
            ['ILIKE(e.field, ANY :texts)', '(e.field ILIKE ANY (?))'],
        ];
    }

    protected function getFunction(): Query\AST\Functions\FunctionNode
    {
        return new ILike('ILIKE');
    }
}
