<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\DriverWrapper;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\API\ExceptionConverter;
use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\VersionAwarePlatformDriver;
use Pfilsx\PostgreSQLDoctrine\DBAL\Platform\PostgreSQLPlatform;
use Pfilsx\PostgreSQLDoctrine\DBAL\Schema\PostgreSQLSchemaManager;
use Pfilsx\PostgreSQLDoctrine\DBAL\Type\EnumType;

final class PostgreSQLDriverWrapper implements VersionAwarePlatformDriver
{
    private Driver $innerDriver;

    public function __construct(Driver $innerDriver)
    {
        $this->innerDriver = $innerDriver;
        if (!Type::hasType(EnumType::NAME)) {
            Type::addType(EnumType::NAME, EnumType::class);
        }
    }


    public function connect(array $params): DriverConnection
    {
        return $this->innerDriver->connect($params);
    }

    public function getDatabasePlatform(): PostgreSQLPlatform
    {
        return new PostgreSQLPlatform();
    }

    public function createDatabasePlatformForVersion($version): PostgreSQLPlatform
    {
        return new PostgreSQLPlatform();
    }

    public function getSchemaManager(Connection $conn, AbstractPlatform $platform): AbstractSchemaManager
    {
        \assert($platform instanceof PostgreSQLPlatform);

        return new PostgreSQLSchemaManager($conn, $platform);
    }

    public function getExceptionConverter(): ExceptionConverter
    {
        return $this->innerDriver->getExceptionConverter();
    }
}