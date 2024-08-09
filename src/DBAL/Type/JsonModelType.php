<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;
use Symfony\Component\Serializer\Debug\TraceableNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

abstract class JsonModelType extends JsonType
{
    private AbstractObjectNormalizer|TraceableNormalizer|null $normalizer = null;

    abstract public static function getTypeName(): string;

    /**
     * @return class-string
     */
    abstract protected static function getModelClass(): string;

    public function getName(): string
    {
        return static::getTypeName();
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!\is_object($value) || get_class($value) !== static::getModelClass()) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        $array = $this->getObjectNormalizer()->normalize($value);

        return parent::convertToDatabaseValue($array, $platform);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        $value = parent::convertToPHPValue($value, $platform);

        if ($value === null) {
            return null;
        }

        return $this->getObjectNormalizer()->denormalize(
            $value,
            static::getModelClass()
        );
    }

    public function setObjectNormalizer(AbstractObjectNormalizer|TraceableNormalizer $normalizer): void
    {
        $this->normalizer = $normalizer;
    }

    protected function getObjectNormalizer(): AbstractObjectNormalizer|TraceableNormalizer
    {
        if ($this->normalizer === null) {
            throw new \RuntimeException('JsonModelType requires object normalizer to be set');
        }

        return $this->normalizer;
    }
}
