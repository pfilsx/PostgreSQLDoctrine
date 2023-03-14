<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Middleware;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Middleware;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Pfilsx\PostgreSQLDoctrine\DBAL\DriverWrapper\PostgreSQLDriverWrapper;

final class PostgreSQLDriverMiddleware implements Middleware
{
    public function wrap(Driver $driver): Driver
    {
        return $driver->getDatabasePlatform() instanceof PostgreSQLPlatform
            ? new PostgreSQLDriverWrapper($driver)
            : $driver
        ;
    }
}
