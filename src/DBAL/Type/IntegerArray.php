<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Type;

use Pfilsx\PostgreSQLDoctrine\Enum\ArrayTypeEnum;

/**
 * Implementation of PostgreSql INTEGER[] data type.
 *
 * @see https://www.postgresql.org/docs/current/arrays.html
 */
class IntegerArray extends AbstractArrayType
{
    protected static function getArrayType(): ArrayTypeEnum
    {
        return ArrayTypeEnum::IntArray;
    }

    public function getName(): string
    {
        return 'integer[]';
    }
}
