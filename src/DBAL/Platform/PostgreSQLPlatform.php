<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Platform;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform as BasePlatform;
use Pfilsx\PostgreSQLDoctrine\DBAL\Schema\EnumTypeAsset;
use Pfilsx\PostgreSQLDoctrine\DBAL\Schema\PostgreSQLSchemaManager;
use Pfilsx\PostgreSQLDoctrine\DBAL\Type\EnumType;

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
     * @return string
     * @throws Exception\InvalidArgumentException
     */
    public function getCreateTypeSql(EnumTypeAsset $type): string
    {
        return "CREATE TYPE {$type->getQuotedName($this)} AS ENUM(" . \implode(', ', $type->getQuotedLabels($this)) . ')';
    }

    public function getCommentOnTypeSql(EnumTypeAsset $type): string
    {
        return "COMMENT ON TYPE {$type->getQuotedName($this)} IS '{$type->getEnumClass()}'";
    }

    public function getAlterTypeSql(EnumTypeAsset $from, EnumTypeAsset $to): string
    {
        throw Exception::notSupported(__METHOD__);
    }

    public function getDropTypeSql(EnumTypeAsset $type): string
    {
        return "DROP TYPE {$type->getQuotedName($this)}";
    }

    protected function initializeDoctrineTypeMappings()
    {
        parent::initializeDoctrineTypeMappings();

        $this->doctrineTypeMapping[EnumType::NAME] = EnumType::NAME;
    }

}
