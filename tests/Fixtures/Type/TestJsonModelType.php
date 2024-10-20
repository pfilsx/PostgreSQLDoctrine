<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Fixtures\Type;


use Pfilsx\PostgreSQLDoctrine\DBAL\Type\JsonModelType;
use Pfilsx\PostgreSQLDoctrine\Tests\Fixtures\Model\TestModel;

final class TestJsonModelType extends JsonModelType
{
    public static function getTypeName(): string
    {
        return 'test_json_model_type';
    }

    protected static function getModelClass(): string
    {
        return TestModel::class;
    }
}