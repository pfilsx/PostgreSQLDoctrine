<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Type;

use Pfilsx\PostgreSQLDoctrine\Enum\ArrayTypeEnum;

/**
 * Implementation of PostgreSql BIGINT[] data type.
 *
 * @see https://www.postgresql.org/docs/current/arrays.html
 */
class BigIntArray extends AbstractArrayType
{
    protected static function getArrayType(): ArrayTypeEnum
    {
        return ArrayTypeEnum::BigIntArray;
    }

    public function getName(): string
    {
        return 'bigint[]';
    }
}
