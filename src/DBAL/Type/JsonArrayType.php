<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Type;

use Pfilsx\PostgreSQLDoctrine\Enum\ArrayTypeEnum;

/**
 * Implementation of PostgreSql JSON(B)[] data type.
 *
 * @see https://www.postgresql.org/docs/current/arrays.html
 */
class JsonArrayType extends AbstractArrayType
{
    protected static function getArrayType(): ArrayTypeEnum
    {
        return ArrayTypeEnum::JsonArray;
    }

    public function getName(): string
    {
        return 'json[]';
    }
}
