<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\Tools;


use Pfilsx\PostgreSQLDoctrine\Enum\ArrayTypeEnum;
use Pfilsx\PostgreSQLDoctrine\Tools\ArrayTypeTool;
use PHPUnit\Framework\TestCase;

/**
 * @see ArrayTypeTool
 */
final class ArrayTypeToolTest extends TestCase
{
    /**
     * @dataProvider providerTestConvertPHPArrayToDatabaseArrayString
     * @see ArrayTypeTool::convertPHPArrayToDatabaseArrayString()
     */
    public function testConvertPHPArrayToDatabaseArrayString(array $in, ArrayTypeEnum $type, string $expectedOut): void
    {
        self::assertSame($expectedOut, ArrayTypeTool::convertPHPArrayToDatabaseArrayString($in, $type));
    }

    public static function providerTestConvertPHPArrayToDatabaseArrayString(): array
    {
        return [
            'empty array' => [
                [],
                ArrayTypeEnum::IntArray,
                '{}',
            ],
            'int array' => [
                [1,2,3],
                ArrayTypeEnum::SmallIntArray,
                '{1,2,3}',
            ],
            'text array' => [
                ['1', 'text', 'text with whitespace and quote "'],
                ArrayTypeEnum::TextArray,
                '{1,"text","text with whitespace and quote \""}'
            ],
            'boolean array without platform' => [
                [1, true, false, 'yes', 'off'],
                ArrayTypeEnum::BooleanArray,
                '{true,true,false,true,false}',
            ],
            'json array' => [
                [['key1' => 1, 'key2' => null, 'key3' => ['sub_key1' => false]], ['key1' => 2]],
                ArrayTypeEnum::JsonArray,
                '{"{\"key1\":1,\"key2\":null,\"key3\":{\"sub_key1\":false}}","{\"key1\":2}"}'
            ],
        ];
    }

    /**
     * @dataProvider providerTestConvertPHPArrayToDatabaseArrayStringOnInvalidData
     * @see ArrayTypeTool::convertPHPArrayToDatabaseArrayString()
     */
    public function testConvertPHPArrayToDatabaseArrayStringOnInvalidData(array $in, ArrayTypeEnum $type, string $exMessage): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage($exMessage);
        ArrayTypeTool::convertPHPArrayToDatabaseArrayString($in, $type);
    }

    public static function providerTestConvertPHPArrayToDatabaseArrayStringOnInvalidData(): array
    {
        return [
            'int array with not int value' => [
                ['test'],
                ArrayTypeEnum::IntArray,
                'Item at key 0 has invalid type. Expected type "int", "string" provided.'
            ],
            'int array with not in range int value' => [
                [32768],
                ArrayTypeEnum::SmallIntArray,
                'Item at key 0 is invalid for "smallint". Expected integer between -32768 and 32767.'
            ],
            'text array' => [
                [false],
                ArrayTypeEnum::TextArray,
                'Item at key 0 has invalid type. Expected type "string", "bool" provided.'
            ],
            'boolean array' => [
                ['test'],
                ArrayTypeEnum::BooleanArray,
                'Item at key 0 has invalid type. Expected boolean, 0, 1 or boolean string literal, \'test\' provided.'
            ],
        ];
    }


    /**
     * @dataProvider providerTestConvertDatabaseArrayStringToPHPArray
     * @see ArrayTypeTool::convertDatabaseArrayStringToPHPArray()
     */
    public function testConvertDatabaseArrayStringToPHPArray(string $in, ArrayTypeEnum $type, array $expectedOut): void
    {
        self::assertSame($expectedOut, ArrayTypeTool::convertDatabaseArrayStringToPHPArray($in, $type));
    }

    public static function providerTestConvertDatabaseArrayStringToPHPArray(): array
    {
        return [
            'empty array' => [
                '{}',
                ArrayTypeEnum::IntArray,
                [],
            ],
            'int array' => [
                '{1,2,3}',
                ArrayTypeEnum::SmallIntArray,
                [1,2,3],
            ],
            'text array' => [
                '{1,text,"text with whitespace and quote \"",null}',
                ArrayTypeEnum::TextArray,
                ['1', 'text', 'text with whitespace and quote "', null],
            ],
            'boolean array without platform' => [
                '{true,false}',
                ArrayTypeEnum::BooleanArray,
                [true, false],
            ],
            'json array' => [
                '{"{\"key1\":1,\"key2\":null,\"key3\":{\"sub_key1\":false}}","{\"key1\":2}"}',
                ArrayTypeEnum::JsonArray,
                [['key1' => 1, 'key2' => null, 'key3' => ['sub_key1' => false]], ['key1' => 2]],
            ],
        ];
    }
}