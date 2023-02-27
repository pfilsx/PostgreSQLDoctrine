<?php

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\Tools;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Pfilsx\PostgreSQLDoctrine\Tests\Fixtures\Enum\TestEnumInterfaceEnum;
use Pfilsx\PostgreSQLDoctrine\Tests\Fixtures\Enum\TestIntBackedEnum;
use Pfilsx\PostgreSQLDoctrine\Tests\Fixtures\Enum\TestStringBackedEnum;
use Pfilsx\PostgreSQLDoctrine\Tests\Fixtures\Enum\TestUnitEnum;
use Pfilsx\PostgreSQLDoctrine\Tools\EnumTool;
use PHPUnit\Framework\TestCase;

final class EnumToolTest extends TestCase
{
    /**
     * @dataProvider providerTestGetEnumTypeNameFromClassName
     * @see EnumTool::getEnumTypeNameFromClassName()
     */
    public function testGetEnumTypeNameFromClassName(string $className, ?string $expectedName): void
    {
        if ($expectedName === null) {
            self::expectException(InvalidArgumentException::class);
        }

        self::assertSame($expectedName, EnumTool::getEnumTypeNameFromClassName($className));
    }

    public static function providerTestGetEnumTypeNameFromClassName(): array
    {
        return [
            'string enum' => [TestStringBackedEnum::class, 'test_string_backed_enum_type'],
            'unit enum' => [TestUnitEnum::class, 'test_unit_enum_type'],
            'enum interface' => [TestEnumInterfaceEnum::class, 'test_enum_interface_enum_type'],
            'int enum' => [TestIntBackedEnum::class, null],
            'other class' => [\stdClass::class, null],
            'random string' => ['test', null],
        ];
    }

    /**
     * @dataProvider providerTestGetEnumLabelsByClassName
     * @see EnumTool::getEnumLabelsByClassName()
     */
    public function testGetEnumLabelsByClassName(string $className, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        }

        self::assertSame([
            'Case1',
            'Case2',
            'Case3',
        ], EnumTool::getEnumLabelsByClassName($className));
    }

    public static function providerTestGetEnumLabelsByClassName(): array
    {
        return [
            'string enum' => [TestStringBackedEnum::class, false],
            'unit enum' => [TestUnitEnum::class, false],
            'enum interface' => [TestEnumInterfaceEnum::class, false],
            'int enum' => [TestIntBackedEnum::class, true],
            'other class' => [\stdClass::class, true],
            'random string' => ['test', true],
        ];
    }
}
