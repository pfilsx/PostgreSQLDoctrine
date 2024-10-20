<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Schema;

use Doctrine\DBAL\Platforms\AbstractPlatform;

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

    public function getQuotedTableName(AbstractPlatform $platform): string
    {
        return $this->getQuotedName($this->table, $platform);
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getQuotedColumnName(AbstractPlatform $platform): string
    {
        return $this->getQuotedName($this->column, $platform);
    }

    public function getDefault(): ?string
    {
        return $this->default;
    }

    private function getQuotedName(string $name, AbstractPlatform $platform): string
    {
        $keywords = $platform->getReservedKeywordsList();
        $parts = explode('.', $name);
        foreach ($parts as $k => $v) {
            $parts[$k] = $keywords->isKeyword($v) ? $platform->quoteIdentifier($v) : $v;
        }

        return implode('.', $parts);
    }
}
