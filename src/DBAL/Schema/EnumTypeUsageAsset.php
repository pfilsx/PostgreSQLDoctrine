<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Schema;

final class EnumTypeUsageAsset
{
    private string $table;

    private string $column;

    private ?string $default;

    public function __construct(string $table, string $column, ?string $default = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->default = $default;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getDefault(): ?string
    {
        return $this->default;
    }
}
