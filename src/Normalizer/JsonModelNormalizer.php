<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Normalizer;

class JsonModelNormalizer
{
    protected const TYPE_SCALAR = 'scalar';

    protected const TYPE_ARRAY = 'array';

    protected const TYPE_OBJECT = 'object';

    protected const TYPE_DATETIME = 'datetime';

    protected const TYPE_ENUM = 'enum';

    protected const TYPE_BACKED_ENUM = 'backed_enum';

    protected const TYPES_MAP = [
        'bool' => self::TYPE_SCALAR,
        'int' => self::TYPE_SCALAR,
        'float' => self::TYPE_SCALAR,
        'string' => self::TYPE_SCALAR,
        'array' => self::TYPE_ARRAY,
        'DateTimeInterface' => self::TYPE_DATETIME,
        'DateTime' => self::TYPE_DATETIME,
        'DateTimeImmutable' => self::TYPE_DATETIME,
    ];

    protected const SCALAR_TYPES = ['bool', 'int', 'float', 'string'];

    /**
     * @var array<string, array{
     *      'constructorParameters': array<string, array{'position': int, 'optional': bool, 'metadata': array{'name': class-string, 'type': string, 'nullable': bool}}>,
     *      'properties': array<string, array{'name': class-string, 'type': string, 'nullable': bool}>
     *  }>
     */
    private array $classMetadataCache = [];

    /**
     * @throws \ReflectionException
     */
    public function normalize(?object $object): ?array
    {
        return $this->normalizeObject($object);
    }

    /**
     * @throws \ReflectionException
     */
    public function denormalize(?array $data, string $type): ?object
    {
        if (!\class_exists($type)) {
            throw new \InvalidArgumentException('JsonModelNormalizer supports only model classes.');
        }

        if ($data === null) {
            return null;
        }

        return $this->denormalizeObject($data, $type);
    }

    /**
     * @throws \ReflectionException
     */
    protected function normalizeObject(?object $object): ?array
    {
        if ($object === null) {
            return null;
        }

        $classname = \get_class($object);
        if (!\array_key_exists($classname, $this->classMetadataCache)) {
            $this->classMetadataCache[$classname] = $this->getClassMetadata($classname);
        }

        $propertiesMap = $this->classMetadataCache[$classname]['properties'];

        $result = [];
        foreach ($propertiesMap as $property => $info) {
            switch ($info['type']) {
                case self::TYPE_SCALAR:
                    $value = $object->$property;

                    break;
                case self::TYPE_ENUM:
                    $value = $object->$property?->name;

                    break;
                case self::TYPE_BACKED_ENUM:
                    $value = $object->$property?->value;

                    break;
                case self::TYPE_DATETIME:
                    $value = $object->$property?->format('Y-m-d H:i:s');

                    break;
                case self::TYPE_ARRAY:
                    $value = $object->$property;
                    foreach ($value as $item) {
                        if (!\is_scalar($item)) {
                            throw new \LogicException('Only scalar arrays are supported for now.');
                        }
                    }

                    break;
                case self::TYPE_OBJECT:
                    $value = $this->normalizeObject($object->$property);

                    break;
                default:
                    throw new \RuntimeException("Unknown property type: {$info['type']}.");
            }
            $result[$property] = $value;
        }

        return $result;
    }

    /**
     * @throws \ReflectionException
     */
    protected function denormalizeObject(?array $data, string $type): ?object
    {
        if ($data === null) {
            return null;
        }

        if (!\array_key_exists($type, $this->classMetadataCache)) {
            $this->classMetadataCache[$type] = $this->getClassMetadata($type);
        }

        $classMetadata = $this->classMetadataCache[$type];

        $parameters = [];
        foreach ($classMetadata['constructorParameters'] as $name => $metadata) {
            if (\array_key_exists($name, $data)) {
                $parameters[$metadata['position']] = $this->denormalizeValue($data[$name], $metadata['metadata'], "$type.$name");
                unset($data[$name]);
            } elseif ($metadata['optional']) {
                continue;
            } else {
                throw new \RuntimeException("Missed not optional constructor parameter in data: $name.");
            }
        }
        \ksort($parameters);

        $result = new $type(...$parameters);

        foreach ($data as $name => $value) {
            $metadata = $classMetadata['properties'][$name] ?? null;

            if ($metadata === null) {
                throw new \RuntimeException("$type has no property with name: $name.");
            }

            $result->$name = $this->denormalizeValue($value, $metadata, "$type.$name");
        }

        return $result;
    }

    /**
     * @param class-string $classname
     *
     * @throws \ReflectionException
     *
     * @return array{
     *     'constructorParameters': array<string, array{'position': int, 'optional': bool, 'metadata': array{'name': class-string, 'type': string, 'nullable': bool}}>,
     *     'properties': array<string, array{'name': class-string, 'type': string, 'nullable': bool}>
     * }
     */
    protected function getClassMetadata(string $classname): array
    {
        $refClass = new \ReflectionClass($classname);
        $constructorParameters = [];
        foreach ($refClass->getConstructor()?->getParameters() ?? [] as $parameter) {
            try {
                $constructorParameters[$parameter->getName()] = [
                    'position' => $parameter->getPosition(),
                    'metadata' => $this->getTypeMetadata($parameter->getType()),
                    'optional' => $parameter->isOptional(),
                ];
            } catch (\RuntimeException) {
                throw new \RuntimeException("Unable to determine type for parameter: $classname::{$parameter->getName()}.");
            } catch (\LogicException) {
                throw new \LogicException("Invalid parameter type for $classname::{$parameter->getName()}. Union and intersection types with not scalar subtypes are not supported in JsonModel.");
            }
        }

        $properties = [];

        foreach ($refClass->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $properties[$property->getName()] = $this->getTypeMetadata($property->getType());
        }

        return [
            'constructorParameters' => $constructorParameters,
            'properties' => $properties,
        ];
    }

    /**
     * @return array{'name': class-string, 'type': string, 'nullable': bool}
     */
    protected function getTypeMetadata(?\ReflectionType $type): array
    {
        if ($type === null) {
            throw new \RuntimeException('Empty type.');
        }

        if ($type instanceof \ReflectionUnionType || $type instanceof \ReflectionIntersectionType) {
            foreach ($type->getTypes() as $subtype) {
                if (!$subtype instanceof \ReflectionNamedType || (!\in_array($subtype->getName(), self::SCALAR_TYPES) && $subtype->getName() !== 'null')) {
                    throw new \LogicException('Invalid type. Union and intersection types with not scalar subtypes are not supported in JsonModel.');
                }
            }

            /** @var class-string $typeName */
            $typeName = (string) $type;
            $propType = self::TYPE_SCALAR;
        } elseif ($type instanceof \ReflectionNamedType) {
            /** @var class-string $typeName */
            $typeName = $type->getName();

            if ($typeName === \DateTimeInterface::class) {
                $typeName = \DateTime::class;
            }

            if (\array_key_exists($typeName, self::TYPES_MAP)) {
                $propType = self::TYPES_MAP[$typeName];
            } elseif (\is_subclass_of($typeName, \BackedEnum::class)) {
                $propType = self::TYPE_BACKED_ENUM;
            } elseif (\is_subclass_of($typeName, \UnitEnum::class)) {
                $propType = self::TYPE_ENUM;
            } elseif (\is_subclass_of($typeName, \DateTimeInterface::class)) {
                $propType = self::TYPE_DATETIME;
            } else {
                $propType = self::TYPE_OBJECT;
            }
        } else {
            throw new \LogicException('Invalid type. Not named types are not supported in JsonModel.');
        }

        return [
            'name' => $typeName,
            'type' => $propType,
            'nullable' => $type->allowsNull(),
        ];
    }

    /**
     * @param mixed                                                         $value
     * @param array{'name': class-string, 'type': string, 'nullable': bool} $metadata
     * @param null|string                                                   $path
     *
     * @throws \ReflectionException
     *
     * @return mixed
     */
    protected function denormalizeValue(mixed $value, array $metadata, ?string $path = null): mixed
    {
        if ($value === null && $metadata['nullable']) {
            return null;
        }
        if ($value === null) {
            throw $this->createInvalidValueException($metadata['name'], $value, $path);
        }

        switch ($metadata['type']) {
            case self::TYPE_SCALAR:
                return $value;
            case self::TYPE_ENUM:
                if (!\in_array($value, $metadata['name']::cases())) {
                    throw $this->createInvalidValueException($metadata['name'], $value, $path);
                }

                return $metadata['name']::$normalizedValue;
            case self::TYPE_BACKED_ENUM:
                $value = $metadata['name']::tryFrom($value);
                if ($value === null) {
                    throw $this->createInvalidValueException($metadata['name'], $value, $path);
                }

                return $value;
            case self::TYPE_DATETIME:
                try {
                    return new $metadata['name']($value);
                } catch (\Throwable) {
                    throw $this->createInvalidValueException('datetime string', $value, $path);
                }
            case self::TYPE_ARRAY:
                if (!\is_array($value)) {
                    throw $this->createInvalidValueException('array', $value, $path);
                }

                return $value;
            case self::TYPE_OBJECT:
                if (!\is_array($value)) {
                    throw $this->createInvalidValueException($metadata['name'], $value, $path);
                }

                return $this->denormalizeObject($value, $metadata['name']);
            default:
                throw new \RuntimeException("Unknown value type: {$metadata['type']}");
        }
    }

    private function createInvalidValueException(string $expectedType, mixed $value, string $path): \UnexpectedValueException
    {
        return new \UnexpectedValueException(
            \sprintf('Data at path "%s" expected to be "%s", "%s" given.', $path, $expectedType, \get_debug_type($value))
        );
    }
}
