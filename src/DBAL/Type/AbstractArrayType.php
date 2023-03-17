<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Pfilsx\PostgreSQLDoctrine\Enum\ArrayTypeEnum;
use Pfilsx\PostgreSQLDoctrine\Tools\ArrayTypeTool;

abstract class AbstractArrayType extends Type
{
    /**
     * @param array<string, mixed> $column
     * @param AbstractPlatform     $platform
     *
     * @throws \Doctrine\DBAL\Exception
     *
     * @return string
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getDoctrineTypeMapping($this->getName());
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!\is_array($value)) {
            throw new ConversionException(\sprintf('Invalid value type. Expected "array", "%s" provided.', \get_debug_type($value)));
        }

        return ArrayTypeTool::convertPHPArrayToDatabaseArrayString($value, static::getArrayType());
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @throws ConversionException
     *
     * @return null|bool[]|int[]|string[]
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?array
    {
        if ($value === null) {
            return null;
        }

        if (!\is_string($value)) {
            throw new ConversionException(\sprintf('Invalid database value type. Expected "string", "%s" provided.', \get_debug_type($value)));
        }

        return ArrayTypeTool::convertDatabaseArrayStringToPHPArray($value, static::getArrayType());
    }

    abstract protected static function getArrayType(): ArrayTypeEnum;
}
