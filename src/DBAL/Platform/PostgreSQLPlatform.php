<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Platform;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform as BasePlatform;
use Doctrine\DBAL\Schema\Column;
use Pfilsx\PostgreSQLDoctrine\DBAL\Schema\EnumTypeAsset;
use Pfilsx\PostgreSQLDoctrine\DBAL\Schema\PostgreSQLSchemaManager;
use Pfilsx\PostgreSQLDoctrine\DBAL\Type\EnumType;
use Pfilsx\PostgreSQLDoctrine\DBAL\Type\JsonModelType;

final class PostgreSQLPlatform extends BasePlatform
{
    public function createSchemaManager(Connection $connection): PostgreSQLSchemaManager
    {
        return new PostgreSQLSchemaManager($connection, $this);
    }

    public function getListEnumTypesSQL(): string
    {
        return 'SELECT pg_type.typname AS name,
                       pg_enum.enumlabel AS label,
                       pg_description.description AS comment
                FROM pg_type
                JOIN pg_enum ON pg_enum.enumtypid = pg_type.oid
                LEFT JOIN pg_description on pg_description.objoid = pg_type.oid
                ORDER BY pg_enum.enumsortorder';
    }

    /**
     * @param EnumTypeAsset $type
     * @throws Exception\InvalidArgumentException
     * @return string
     */
    public function getCreateTypeSql(EnumTypeAsset $type): string
    {
        return "CREATE TYPE {$type->getQuotedName($this)} AS ENUM(" . \implode(', ', $type->getQuotedLabels($this)) . ')';
    }

    public function getCommentOnTypeSql(EnumTypeAsset $type): string
    {
        return "COMMENT ON TYPE {$type->getQuotedName($this)} IS '{$type->getEnumClass()}'";
    }

    /**
     * @param EnumTypeAsset $from
     * @param EnumTypeAsset $to
     * @throws Exception
     * @return string[]
     */
    public function getAlterTypeSql(EnumTypeAsset $from, EnumTypeAsset $to): array
    {
        $fromLabels = $from->getLabels();
        $toLabels = $to->getLabels();

        $result = [];
        foreach (array_diff($toLabels, $fromLabels) as $label) {
            $result[] = "ALTER TYPE {$to->getQuotedName($this)} ADD VALUE {$this->quoteEnumLabel($label)}";
        }

        if (count(array_diff($fromLabels, $toLabels)) > 0) {
            throw new Exception('Enum labels reduction is not supported in automatic generation');
        }

        return $result;
    }

    public function getDropTypeSql(EnumTypeAsset $type): string
    {
        return "DROP TYPE {$type->getQuotedName($this)}";
    }

    public function quoteEnumLabel(mixed $label): int|string
    {
        if (\is_string($label)) {
            return $this->quoteStringLiteral($label);
        } elseif (\is_int($label)) {
            return $label;
        } else {
            throw new InvalidArgumentException('Invalid custom type labels specified. Only string and integers are supported');
        }
    }

    public function columnsEqual(Column $column1, Column $column2): bool
    {
        if (parent::columnsEqual($column1, $column2)) {
            return true;
        }

        $type1 = $column1->getType();
        $type2 = $column2->getType();

        if (!is_subclass_of($type1, JsonModelType::class) && !is_subclass_of($type2, JsonModelType::class)) {
            return false;
        }

        return is_subclass_of($type1, $type2::class) || is_subclass_of($type2, $type1::class);

    }

    protected function initializeDoctrineTypeMappings(): void
    {
        parent::initializeDoctrineTypeMappings();

        $this->doctrineTypeMapping[EnumType::NAME] = EnumType::NAME;
    }

}
