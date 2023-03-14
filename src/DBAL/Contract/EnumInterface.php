<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Contract;

interface EnumInterface
{
    /**
     * @return string[]
     */
    public static function cases(): array;
}
