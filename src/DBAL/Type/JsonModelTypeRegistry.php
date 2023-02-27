<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Type;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Debug\TraceableNormalizer;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class JsonModelTypeRegistry
{
    private static AbstractObjectNormalizer|TraceableNormalizer $objectNormalizer;

    private static array $typesMap = [];

    /**
     * @param string $name
     * @param class-string $className
     * @param bool $override
     * @throws Exception
     * @return void
     */
    public static function addType(string $name, string $className, bool $override = false): void
    {
        if (!$override && array_key_exists($name, self::$typesMap)) {
            throw Exception::typeExists($name);
        }

        if (!class_exists($className) || !is_subclass_of($className, JsonModelType::class)) {
            throw new Exception(
                sprintf('Type class name should be a subclass of %s. %s provided', JsonModelType::class, $className)
            );
        }

        self::$typesMap[$name] = $className;
    }

    public static function hasType(string $name): bool
    {
        return array_key_exists($name, self::$typesMap);
    }

    public static function getObjectNormalizer(): AbstractObjectNormalizer|TraceableNormalizer
    {
        return self::$objectNormalizer ??= new ObjectNormalizer(
            nameConverter: new CamelCaseToSnakeCaseNameConverter(),
            propertyTypeExtractor: new ReflectionExtractor()
        );
    }
    public static function setObjectNormalizer(AbstractObjectNormalizer|TraceableNormalizer $objectNormalizer): void
    {
        self::$objectNormalizer = $objectNormalizer;
    }
    public static function registerTypes(): void
    {
        $typeRegistry = Type::getTypeRegistry();
        $objectNormalizer = self::getObjectNormalizer();
        foreach (self::$typesMap as $name => $className) {
            /** @var JsonModelType $type */
            $type = new $className();
            $type->setObjectNormalizer($objectNormalizer);

            if ($typeRegistry->has($name)) {
                $typeRegistry->override($name, $type);
            } else {
                $typeRegistry->register($name, $type);
            }
        }
    }
}