<?php
declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tools;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Pfilsx\PostgreSQLDoctrine\DBAL\Contract\EnumInterface;

final class EnumTool
{
    /**
     * @var array<string, bool>
     */
    private static array $checkMap = [];
    /**
     * @param class-string<EnumInterface|\UnitEnum> $className
     * @throws InvalidArgumentException
     * @return string
     */
    public static function getEnumTypeNameFromClassName(string $className): string
    {
        self::checkEnumExistsAndValid($className);

        $classNameParts = \explode('\\', $className);

        return \strtolower(\preg_replace('/(?<=[a-z])([A-Z])/', '_$1', \array_pop($classNameParts))) . '_type';
    }

    /**
     * @param class-string<EnumInterface|\UnitEnum> $className
     * @throws InvalidArgumentException
     * @return array<int|string>
     */
    public static function getEnumLabelsByClassName(string $className): array
    {
        self::checkEnumExistsAndValid($className);

        return \array_map(
            static function (mixed $case) {
                if ($case instanceof \BackedEnum) {
                    return $case->value;
                }
                if ($case instanceof \UnitEnum) {
                    return $case->name;
                }

                return $case;
            },
            $className::cases()
        );
    }

    private static function checkEnumExistsAndValid(string $className): void
    {
        if (self::$checkMap[$className] ?? false) {
            return;
        }

        if (!\enum_exists($className) && (!\class_exists($className) || !is_subclass_of($className, EnumInterface::class))) {
            throw new InvalidArgumentException(
                sprintf('Invalid enum className specified: %s. Enum class has to be a php8 enum or implements %s', $className, EnumInterface::class)
            );
        }

        if (enum_exists($className) && self::isIntBackedEnum($className)) {
            throw new InvalidArgumentException(
                sprintf('Invalid enum className specified: %s. PostgreSQL supports only string values for enums', $className)
            );
        }

        self::$checkMap[$className] = true;
    }

    /**
     * @param class-string<\UnitEnum> $className
     * @return bool
     */
    private static function isIntBackedEnum(string $className): bool
    {
        $fistCase = ($className::cases()[0] ?? null);

        return $fistCase instanceof \BackedEnum && is_int($fistCase->value);
    }
}
