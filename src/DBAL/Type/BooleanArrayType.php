<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Type;

use Pfilsx\PostgreSQLDoctrine\Enum\ArrayTypeEnum;

/**
 * Implementation of PostgreSql BOOL[] data type.
 *
 * @see https://www.postgresql.org/docs/current/arrays.html
 */
class BooleanArrayType extends AbstractArrayType
{
    protected static function getArrayType(): ArrayTypeEnum
    {
        return ArrayTypeEnum::BooleanArray;
    }

    public function getName(): string
    {
        return 'bool[]';
    }
}
