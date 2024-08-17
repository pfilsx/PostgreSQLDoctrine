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

class PostgreSQLPlatform extends BasePlatform
{
    public function createSchemaManager(Connection $connection): PostgreSQLSchemaManager
    {
        return new PostgreSQLSchemaManager($connection, $this);
    }

    public function getListEnumTypesSQL(): string
    {
        return 'WITH types AS (
                    SELECT pg_type.typname            AS name,
                           pg_enum.enumlabel          AS label,
                           pg_enum.enumsortorder      AS label_order,
                           pg_description.description AS comment,
                           pg_class.relname           AS usage_table,
                           pg_attribute.attname       AS usage_column,
                           PG_GET_EXPR(pg_attrdef.adbin, pg_attrdef.adrelid) AS usage_default
                    FROM pg_type
                        JOIN pg_enum ON pg_enum.enumtypid = pg_type.oid
                        LEFT JOIN pg_description ON pg_description.objoid = pg_type.oid
                        LEFT JOIN pg_depend ON pg_depend.refobjid = pg_type.oid
                        LEFT JOIN pg_class ON pg_class.oid = pg_depend.objid
                        LEFT JOIN pg_attribute ON pg_attribute.attrelid = pg_class.oid AND pg_attribute.atttypid = pg_type.oid
                        LEFT JOIN pg_attrdef ON pg_attrdef.adrelid = pg_class.oid AND pg_attrdef.adnum = pg_attribute.attnum
                )
                SELECT types.name,
                       types.comment,
                       JSON_AGG(DISTINCT JSONB_BUILD_OBJECT(\'label\', types.label, \'order\', types.label_order)) AS labels,
                       JSON_AGG(DISTINCT JSONB_BUILD_OBJECT(\'table\', types.usage_table, \'column\', types.usage_column, \'default\', types.usage_default)) FILTER (WHERE types.usage_table IS NOT NULL AND types.usage_column IS NOT NULL) AS usages
                FROM types
                GROUP BY types.name, types.comment'
        ;
    }

    /**
     * @param EnumTypeAsset $type
     *
     * @throws InvalidArgumentException
     *
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
     *
     * @throws Exception
     *
     * @return string[]
     */
    public function getAlterTypeSql(EnumTypeAsset $from, EnumTypeAsset $to): array
    {
        $fromLabels = $from->getLabels();
        $toLabels = $to->getLabels();

        $result = [];
        $typeName = $to->getQuotedName($this);

        $removedLabels = array_diff($fromLabels, $toLabels);

        if (count($removedLabels) < 1) {
            foreach (array_diff($toLabels, $fromLabels) as $label) {
                $result[] = "ALTER TYPE {$typeName} ADD VALUE {$this->quoteEnumLabel($label)}";
            }

            return $result;
        }

        $result[] = "ALTER TYPE {$typeName} RENAME TO {$typeName}_old";
        $result[] = $this->getCreateTypeSql($to);
        $result[] = $this->getCommentOnTypeSql($to);

        foreach ($to->getUsages() as $usage) {
            $tableName = $usage->getQuotedTableName($this);
            $columnName = $usage->getQuotedColumnName($this);
            if (($default = $usage->getDefault()) !== null) {
                $result[] = sprintf('ALTER TABLE %s ALTER COLUMN %s DROP DEFAULT', $tableName, $columnName);
            }
            $result[] = sprintf(
                'ALTER TABLE %1$s ALTER COLUMN %2$s TYPE %3$s USING LOWER(%2$s::text)::%3$s',
                $tableName,
                $columnName,
                $typeName
            );

            if ($default !== null) {
                $result[] = sprintf(
                    'ALTER TABLE %s ALTER COLUMN %s SET DEFAULT %s',
                    $tableName,
                    $columnName,
                    $this->quoteEnumLabel($default)
                );
            }
        }

        $result[] = "DROP TYPE {$typeName}_old";

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
