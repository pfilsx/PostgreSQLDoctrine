<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Type;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use Pfilsx\PostgreSQLDoctrine\Normalizer\JsonModelNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class JsonModelTypeRegistry
{
    /**
     * @var null|DenormalizerInterface|JsonModelNormalizer|NormalizerInterface
     */
    private static mixed $objectNormalizer = null;

    private static array $typesMap = [];

    /**
     * @param string       $name
     * @param class-string $className
     * @param bool         $override
     *
     * @throws Exception
     *
     * @return void
     */
    public static function addType(string $name, string $className, bool $override = false): void
    {
        if (!$override && array_key_exists($name, self::$typesMap)) {
            throw Exception::typeExists($name);
        }

        if (!class_exists($className) || !is_subclass_of($className, JsonModelType::class)) {
            throw new Exception(sprintf('Type class name should be a subclass of %s. %s provided', JsonModelType::class, $className));
        }

        self::$typesMap[$name] = $className;
    }

    public static function hasType(string $name): bool
    {
        return array_key_exists($name, self::$typesMap);
    }

    /**
     * @return DenormalizerInterface|JsonModelNormalizer|NormalizerInterface
     */
    public static function getObjectNormalizer(): mixed
    {
        return self::$objectNormalizer ??= new JsonModelNormalizer();
    }

    /**
     * @param DenormalizerInterface|JsonModelNormalizer|NormalizerInterface $objectNormalizer
     */
    public static function setObjectNormalizer(mixed $objectNormalizer): void
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
