<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Schema;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractAsset;
use Pfilsx\PostgreSQLDoctrine\DBAL\Contract\EnumInterface;
use Pfilsx\PostgreSQLDoctrine\Tools\EnumTool;

final class EnumTypeAsset extends AbstractAsset
{
    /**
     * @var array<int|string>
     */
    private array $labels;
    private string $enumClass;

    /**
     * @param string $name
     * @param string $className
     * @param array<int|string> $labels
     * @throws InvalidArgumentException
     */
    public function __construct(string $name, string $className, array $labels = [])
    {
        if ($name === '') {
            throw new InvalidArgumentException('Invalid custom type name specified');
        }

        $this->_setName($name);
        $this->enumClass = $className;
        $this->labels = $labels;
    }

    /**
     * @param class-string<EnumInterface|\UnitEnum> $className
     * @throws InvalidArgumentException
     * @return static
     */
    public static function fromEnumClassName(string $className): self
    {
        return new self(
            EnumTool::getEnumTypeNameFromClassName($className),
            $className,
            EnumTool::getEnumLabelsByClassName($className)
        );
    }

    /**
     * @return array<int|string>
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    public function getEnumClass(): string
    {
        return $this->enumClass;
    }

    /**
     * @param AbstractPlatform $platform
     * @throws InvalidArgumentException
     * @return array<int|string>
     */
    public function getQuotedLabels(AbstractPlatform $platform): array
    {
        $result = [];
        foreach ($this->labels as $label) {
            if (\is_string($label)) {
                $result[] = $platform->quoteStringLiteral($label);
            } elseif (\is_int($label)) {
                $result[] = $label;
            } else {
                throw new InvalidArgumentException('Invalid custom type labels specified. Only string and integers are supported');
            }
        }

        return $result;
    }
}
