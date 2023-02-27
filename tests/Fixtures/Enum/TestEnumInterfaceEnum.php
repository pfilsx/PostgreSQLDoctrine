<?php

namespace Pfilsx\PostgreSQLDoctrine\Tests\Fixtures\Enum;

use Pfilsx\PostgreSQLDoctrine\DBAL\Contract\EnumInterface;

final class TestEnumInterfaceEnum implements EnumInterface
{
    public const CASE1 = 'Case1';

    public const CASE2 = 'Case2';

    public const CASE3 = 'Case3';

    public static function cases(): array
    {
        return [self::CASE1, self::CASE2, self::CASE3];
    }
}
