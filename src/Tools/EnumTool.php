<?php

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
     * @param class-string<\UnitEnum|EnumInterface> $className
     * @return string
     * @throws InvalidArgumentException
     */
    public static function getEnumTypeNameFromClassName(string $className): string
    {
        self::checkEnumExists($className);

        $classNameParts = \explode('\\', $className);

        return \strtolower(\preg_replace('/(?<=[a-z])([A-Z])/', '_$1', \array_pop($classNameParts))) . '_type';
    }

    /**
     * @param class-string<\UnitEnum|EnumInterface> $className
     * @return array<string|int>
     * @throws InvalidArgumentException
     */
    public static function getEnumLabelsByClassName(string $className): array
    {
        self::checkEnumExists($className);

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

    private static function checkEnumExists(string $className): void
    {
        if (self::$checkMap[$className] ?? false) {
            return;
        }

        if (!\enum_exists($className) && (!\class_exists($className) || is_subclass_of($className, EnumInterface::class))) {
            throw new InvalidArgumentException(
                sprintf('Invalid enum className specified: %s. Enum class has to be a php8 enum or implements %s', $className, EnumInterface::class)
            );
        }

        self::$checkMap[$className] = true;
    }
}
