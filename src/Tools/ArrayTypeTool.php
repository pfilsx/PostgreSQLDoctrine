<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tools;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Pfilsx\PostgreSQLDoctrine\Enum\ArrayTypeEnum;

final class ArrayTypeTool
{
    private const INT_RANGES = [
        'smallint' => [
            'min' => '-32768',
            'max' => '32767',
        ],
        'integer' => [
            'min' => '-2147483648',
            'max' => '2147483647',
        ],
        'bigint' => [
            'min' => '-9223372036854775807',
            'max' => '9223372036854775807',
        ],
    ];
    private const BOOL_LITERALS = [
        't' => 'true',
        'true' => 'true',
        'y' => 'true',
        'yes' => 'true',
        'on' => 'true',
        '1' => 'true',
        'f' => 'false',
        'false' => 'false',
        'n' => 'false',
        'no' => 'false',
        'off' => 'false',
        '0' => 'false',
    ];

    /**
     * @param bool[]|int[]|string[] $array
     * @param ArrayTypeEnum         $type
     * @param null|AbstractPlatform $platform
     *
     * @return string
     */
    public static function convertPHPArrayToDatabaseArrayString(array $array, ArrayTypeEnum $type, ?AbstractPlatform $platform = null): string
    {
        if ($array === []) {
            return '{}';
        }

        $preparedArray = match ($type) {
            ArrayTypeEnum::SmallIntArray, ArrayTypeEnum::IntArray, ArrayTypeEnum::BigIntArray => self::convertIntPHPArrayToDatabaseArray($array, $type),
            ArrayTypeEnum::TextArray => self::convertStringPHPArrayToDatabaseArray($array),
            ArrayTypeEnum::BooleanArray => self::convertBooleanPHPArrayToDatabaseArray($array, $platform),
            ArrayTypeEnum::JsonArray => array_map(static fn ($row) => '"' . json_encode($row) . '"', $array),
        };

        return '{' . implode(',', $preparedArray) . '}';
    }

    /**
     * @param string                $value
     * @param ArrayTypeEnum         $type
     * @param null|AbstractPlatform $platform
     *
     * @throws \JsonException
     *
     * @return bool[]|int[]|string[]
     */
    public static function convertDatabaseArrayStringToPHPArray(string $value, ArrayTypeEnum $type, ?AbstractPlatform $platform = null): array
    {
        if ($value === '{}') {
            return [];
        }

        return match ($type) {
            ArrayTypeEnum::SmallIntArray, ArrayTypeEnum::IntArray, ArrayTypeEnum::BigIntArray => self::convertDatabaseArrayStringToIntPHPArray($value, $type),
            ArrayTypeEnum::TextArray => self::convertDatabaseArrayStringToStringPHPArray($value),
            ArrayTypeEnum::BooleanArray => self::convertDatabaseArrayStringToBoolPHPArray($value, $platform),
            ArrayTypeEnum::JsonArray => self::convertDatabaseArrayJsonStringToPHPArray($value),
        };
    }

    /**
     * @param int[]         $array
     * @param ArrayTypeEnum $type
     *
     * @return string[]
     */
    private static function convertIntPHPArrayToDatabaseArray(array $array, ArrayTypeEnum $type): array
    {
        $min = self::INT_RANGES[$type->value]['min'];
        $max = self::INT_RANGES[$type->value]['max'];

        foreach ($array as $key => $value) {
            if (!is_int($value) && (!is_string($value) || !preg_match('/^-?\d+$/', $value))) {
                throw new \InvalidArgumentException(\sprintf('Item at key %s has invalid type. Expected type "int", "%s" provided.', $key, \get_debug_type($value)));
            }

            $value = (string) $value;

            if ($value < $min || $value > $max) {
                throw new \InvalidArgumentException(\sprintf('Item at key %s is invalid for "%s". Expected integer between %s and %s.', $key, $type->value, $min, $max));
            }
        }

        return $array;
    }

    /**
     * @param string[] $array
     *
     * @return string[]
     */
    private static function convertStringPHPArrayToDatabaseArray(array $array): array
    {
        foreach ($array as $key => &$value) {
            if (!is_string($value)) {
                throw new \InvalidArgumentException(\sprintf('Item at key %s has invalid type. Expected type "string", "%s" provided.', $key, \get_debug_type($value)));
            }

            $value = is_numeric($value) || ctype_digit($value) ? $value : '"' . \addcslashes($value, '"\\') . '"';
        }

        return $array;
    }

    /**
     * @param bool[]|int[]|string[] $array
     * @param null|AbstractPlatform $platform
     *
     * @return string[]
     */
    private static function convertBooleanPHPArrayToDatabaseArray(array $array, ?AbstractPlatform $platform): array
    {
        if ($platform !== null) {
            return $platform->convertBooleansToDatabaseValue($array);
        }

        foreach ($array as $key => $value) {
            if (is_bool($value) || (is_int($value) && in_array($value, [0, 1]))) {
                $array[$key] = $value ? 'true' : 'false';

                continue;
            }

            if (is_string($value) && array_key_exists($value, self::BOOL_LITERALS)) {
                $array[$key] = self::BOOL_LITERALS[$value];

                continue;
            }

            throw new \InvalidArgumentException(\sprintf('Item at key %s has invalid type. Expected boolean, 0, 1 or boolean string literal, "%s" provided.', $key, \var_export($value, true)));
        }

        return $array;
    }

    /**
     * @param string        $value
     * @param ArrayTypeEnum $type
     *
     * @return int[]
     */
    private static function convertDatabaseArrayStringToIntPHPArray(string $value, ArrayTypeEnum $type): array
    {
        $min = self::INT_RANGES[$type->value]['min'];
        $max = self::INT_RANGES[$type->value]['max'];

        $array = explode(',', trim($value, '{}'));

        foreach ($array as &$item) {
            if (!\preg_match('/^-?\d+$/', $item) || $item < $min || $item > $max) {
                throw new \InvalidArgumentException(sprintf('Given array item with value "%s" cannot be converted to %s. Expected integer between %s and %s.', $item, $type->value, $min, $max));
            }

            $item = (int) $item;
        }

        return $array;
    }

    /**
     * @param string $value
     *
     * @return string[]
     */
    private static function convertDatabaseArrayStringToStringPHPArray(string $value): array
    {
        $array = \str_getcsv(\trim($value, '{}'));

        foreach ($array as $key => $item) {
            if ($item === null) {
                unset($array[$key]);

                break;
            }

            $array[$key] = \stripslashes($item);
        }

        return $array;
    }

    /**
     * @param string                $value
     * @param null|AbstractPlatform $platform
     *
     * @return bool[]
     */
    private static function convertDatabaseArrayStringToBoolPHPArray(string $value, ?AbstractPlatform $platform): array
    {
        $array = explode(',', trim($value, '{}'));

        if ($platform !== null) {
            return array_map(
                static fn ($item): ?bool => $platform->convertFromBoolean($item),
                $array
            );
        }

        foreach ($array as &$item) {
            $lowerItem = strtolower(trim($item, '"\''));
            $item = (self::BOOL_LITERALS[$lowerItem] ?? $lowerItem) !== 'false' && (bool) $lowerItem;
        }

        return $array;
    }

    private static function convertDatabaseArrayJsonStringToPHPArray(string $value): array
    {
        return json_decode(
            '[' . stripcslashes(preg_replace('/\"(\{.+\})\"/U', '$1', trim($value, '{}'))) . ']',
            true,
            512,
            JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING
        );
    }
}
