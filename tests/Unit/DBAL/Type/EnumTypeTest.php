<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\DBAL\Type;

use Pfilsx\PostgreSQLDoctrine\DBAL\Platform\PostgreSQLPlatform;
use Pfilsx\PostgreSQLDoctrine\DBAL\Type\EnumType;
use Pfilsx\PostgreSQLDoctrine\Tests\Fixtures\Enum\TestStringBackedEnum;
use Pfilsx\PostgreSQLDoctrine\Tests\Fixtures\Enum\TestUnitEnum;
use PHPUnit\Framework\TestCase;

/**
 * @see EnumType
 */
final class EnumTypeTest extends TestCase
{
    private PostgreSQLPlatform $platform;

    private EnumType $type;

    public function setUp(): void
    {
        $this->platform = $this->createMock(PostgreSQLPlatform::class);
        $this->type = new EnumType();
    }

    public function testGetName(): void
    {
        self::assertEquals('enum', $this->type->getName());
    }

    public function testGetSqlDeclaration(): void
    {
        self::assertSame('test_string_backed_enum_type', $this->type->getSQLDeclaration([
            'enumType' => TestStringBackedEnum::class,
        ], $this->platform));
    }

    public function testConvertToDatabaseValue(): void
    {
        self::assertSame('Case1', $this->type->convertToDatabaseValue(TestStringBackedEnum::Case1, $this->platform));
        self::assertSame('Case1', $this->type->convertToDatabaseValue(TestUnitEnum::Case1, $this->platform));
        self::assertSame(null, $this->type->convertToDatabaseValue(null, $this->platform));
    }
}
