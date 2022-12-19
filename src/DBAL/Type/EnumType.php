<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Type;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Pfilsx\PostgreSQLDoctrine\Tools\EnumTool;

class EnumType extends Type
{
    public const NAME = 'enum';

    /**
     * @param array<string,mixed> $column
     * @param AbstractPlatform $platform
     * @throws Exception\InvalidArgumentException
     * @return string
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if (!$platform instanceof PostgreSQLPlatform) {
            Exception::notSupported('EnumType not supported by this platform.');
        }

        $enumClass = $column['enumType'] ?? null;

        if ($enumClass === null) {
            throw new \InvalidArgumentException("Incomplete definition. 'enumType' required.");
        }

        return EnumTool::getEnumTypeNameFromClassName($enumClass);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @param int|string|\UnitEnum $value
     * @param AbstractPlatform $platform
     * @return int|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): int|string
    {
        if ($value instanceof \BackedEnum) {
            return $value->value;
        } elseif ($value instanceof \UnitEnum) {
            return $value->name;
        } else {
            return $value;
        }
    }
}