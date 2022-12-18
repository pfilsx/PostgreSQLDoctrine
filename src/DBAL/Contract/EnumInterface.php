<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Contract;


interface EnumInterface
{
    /**
     * @return array<string|int>
     */
    public static function cases(): array;
}