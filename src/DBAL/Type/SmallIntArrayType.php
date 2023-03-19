<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Type;

use Pfilsx\PostgreSQLDoctrine\Enum\ArrayTypeEnum;

/**
 * Implementation of PostgreSql SMALLINT[] data type.
 *
 * @see https://www.postgresql.org/docs/current/arrays.html
 */
class SmallIntArrayType extends AbstractArrayType
{
    protected static function getArrayType(): ArrayTypeEnum
    {
        return ArrayTypeEnum::SmallIntArray;
    }

    public function getName(): string
    {
        return 'smallint[]';
    }
}
