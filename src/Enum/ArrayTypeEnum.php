<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Enum;

enum ArrayTypeEnum: string
{
    case SmallIntArray = 'smallint';
    case IntArray = 'integer';
    case BigIntArray = 'bigint';
    case TextArray = 'text';
    case BooleanArray = 'boolean';
}
