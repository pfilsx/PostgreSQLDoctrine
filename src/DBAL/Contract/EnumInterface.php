<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Contract;

interface EnumInterface
{
    /**
     * @return array<int|string>
     */
    public static function cases(): array;
}