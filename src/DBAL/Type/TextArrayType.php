<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Type;

use Pfilsx\PostgreSQLDoctrine\Enum\ArrayTypeEnum;

/**
 * Implementation of PostgreSql TEXT[] data type.
 *
 * @see https://www.postgresql.org/docs/current/arrays.html
 */
class TextArrayType extends AbstractArrayType
{
    protected static function getArrayType(): ArrayTypeEnum
    {
        return ArrayTypeEnum::TextArray;
    }

    public function getName(): string
    {
        return 'text[]';
    }
}
