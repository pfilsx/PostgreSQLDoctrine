<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\DBAL\Type;


use Pfilsx\PostgreSQLDoctrine\DBAL\Platform\PostgreSQLPlatform;
use Pfilsx\PostgreSQLDoctrine\DBAL\Type\BigIntArrayType;
use Pfilsx\PostgreSQLDoctrine\DBAL\Type\BooleanArrayType;
use Pfilsx\PostgreSQLDoctrine\DBAL\Type\IntegerArrayType;
use Pfilsx\PostgreSQLDoctrine\DBAL\Type\JsonArrayType;
use Pfilsx\PostgreSQLDoctrine\DBAL\Type\SmallIntArrayType;
use Pfilsx\PostgreSQLDoctrine\DBAL\Type\TextArrayType;
use PHPUnit\Framework\TestCase;

final class ArrayTypesTest extends TestCase
{
    private PostgreSQLPlatform $platform;

    public function setUp(): void
    {
        $this->platform = $this->createMock(PostgreSQLPlatform::class);
    }

    /**
     * @see SmallIntArrayType
     */
    public function testSmallIntArrayType(): void
    {
        $type = new SmallIntArrayType();
        self::assertSame('smallint[]', $type->getName());
        self::assertSame('{-32768,0,32767}', $type->convertToDatabaseValue([-32768, 0, 32767], $this->platform));
        self::assertSame([-32768, 0, 32767], $type->convertToPHPValue('{-32768,0,32767}', $this->platform));
    }

    /**
     * @see IntegerArrayType
     */
    public function testIntArrayType(): void
    {
        $type = new IntegerArrayType();
        self::assertSame('integer[]', $type->getName());
        self::assertSame('{-2147483648,0,2147483647}', $type->convertToDatabaseValue([-2147483648, 0, 2147483647], $this->platform));
        self::assertSame([-2147483648, 0, 2147483647], $type->convertToPHPValue('{-2147483648,0,2147483647}', $this->platform));
    }

    /**
     * @see BigIntArrayType
     */
    public function testBigIntArrayType(): void
    {
        $type = new BigIntArrayType();
        self::assertSame('bigint[]', $type->getName());
        self::assertSame('{-9223372036854775807,0,9223372036854775807}', $type->convertToDatabaseValue([-9223372036854775807, 0, 9223372036854775807], $this->platform));
        self::assertSame([-9223372036854775807, 0, 9223372036854775807], $type->convertToPHPValue('{-9223372036854775807,0,9223372036854775807}', $this->platform));
    }

    /**
     * @see BooleanArrayType
     */
    public function testBooleanArrayType(): void
    {
        $type = new BooleanArrayType();
        self::assertSame('bool[]', $type->getName());
        self::assertSame('{true,false,true,false}', $type->convertToDatabaseValue([1, false, 'yes', 'off'], $this->platform));
        self::assertSame([true, false], $type->convertToPHPValue('{true,false}', $this->platform));
    }

    /**
     * @see TextArrayType
     */
    public function testTextArrayType(): void
    {
        $type = new TextArrayType();
        self::assertSame('text[]', $type->getName());
        self::assertSame('{1,"text"}', $type->convertToDatabaseValue(['1', 'text'], $this->platform));
        self::assertSame(['1', 'text'], $type->convertToPHPValue('{1,"text"}', $this->platform));
    }

    /**
     * @see JsonArrayType
     */
    public function testJsonArrayType(): void
    {
        $type = new JsonArrayType();
        self::assertSame('json[]', $type->getName());
        self::assertSame('{"{\"key\":1}","{\"key\":2}"}', $type->convertToDatabaseValue([['key' => 1], ['key' => 2]], $this->platform));
        self::assertSame([['key' => 1], ['key' => 2]], $type->convertToPHPValue('{"{\"key\":1}","{\"key\":2}"}', $this->platform));
    }
}