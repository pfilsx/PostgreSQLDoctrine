<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Unit\DBAL\Type;

use Pfilsx\PostgreSQLDoctrine\DBAL\Platform\PostgreSQLPlatform;
use Pfilsx\PostgreSQLDoctrine\DBAL\Type\JsonModelType;
use Pfilsx\PostgreSQLDoctrine\Tests\Fixtures\Model\TestModel;
use Pfilsx\PostgreSQLDoctrine\Tests\Fixtures\Type\TestJsonModelType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @see JsonModelType
 */
final class JsonModelTypeTest extends TestCase
{
    private PostgreSQLPlatform $platform;
    private TestJsonModelType $type;

    public function setUp(): void
    {
        $this->platform = $this->createMock(PostgreSQLPlatform::class);
        $this->type = new TestJsonModelType();
        $this->type->setObjectNormalizer(new ObjectNormalizer());
    }

    public function testGetName(): void
    {
        self::assertSame('test_json_model_type', $this->type->getName());
    }

    public function testConvertToDatabaseValue(): void
    {
        $model = new TestModel();
        $model->id = 1;
        $model->setText('test');
        self::assertSame('{"text":"test","id":1}', $this->type->convertToDatabaseValue($model, $this->platform));
    }

    public function testConvertToPHPValue(): void
    {
        $model = $this->type->convertToPHPValue('{"id":1,"text":"test"}', $this->platform);
        self::assertInstanceOf(TestModel::class, $model);

        self::assertSame(1, $model->id);
        self::assertSame('test', $model->getText());
    }
}
