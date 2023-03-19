<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Implementation of PostgreSql TSVECTOR data type.
 *
 * @see https://www.postgresql.org/docs/current/datatype-textsearch.html#DATATYPE-TSVECTOR
 */
class TSVectorType extends Type
{
    /**
     * @param array<string, mixed> $column
     * @param AbstractPlatform     $platform
     *
     * @return string
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'TSVECTOR';
    }

    public function getName(): string
    {
        return 'tsvector';
    }
}
