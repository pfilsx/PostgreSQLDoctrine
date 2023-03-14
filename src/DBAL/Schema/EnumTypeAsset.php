<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Schema;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractAsset;
use Pfilsx\PostgreSQLDoctrine\DBAL\Contract\EnumInterface;
use Pfilsx\PostgreSQLDoctrine\DBAL\Platform\PostgreSQLPlatform;
use Pfilsx\PostgreSQLDoctrine\Tools\EnumTool;

final class EnumTypeAsset extends AbstractAsset
{
    /**
     * @var string[]
     */
    private array $labels;
    private string $enumClass;
    /**
     * @var EnumTypeUsageAsset[]
     */
    private array $usages;

    /**
     * @param string               $name
     * @param string               $className
     * @param string[]             $labels
     * @param EnumTypeUsageAsset[] $usages
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $name, string $className, array $labels = [], array $usages = [])
    {
        if ($name === '') {
            throw new InvalidArgumentException('Invalid custom type name specified');
        }

        $this->_setName($name);
        $this->enumClass = $className;
        $this->labels = $labels;
        $this->usages = $usages;
    }

    /**
     * @param class-string<EnumInterface|\UnitEnum> $className
     *
     * @throws InvalidArgumentException
     *
     * @return static
     */
    public static function fromEnumClassName(string $name, string $className): self
    {
        return new self(
            $name,
            $className,
            EnumTool::getEnumLabelsByClassName($className)
        );
    }

    /**
     * @return string[]
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    public function getEnumClass(): string
    {
        return $this->enumClass;
    }

    public function addUsage(EnumTypeUsageAsset $usage): self
    {
        $this->usages[] = $usage;

        return $this;
    }

    /**
     * @return EnumTypeUsageAsset[]
     */
    public function getUsages(): array
    {
        return $this->usages;
    }

    /**
     * @param AbstractPlatform $platform
     *
     * @throws InvalidArgumentException
     *
     * @return array<int|string>
     */
    public function getQuotedLabels(AbstractPlatform $platform): array
    {
        if (!$platform instanceof PostgreSQLPlatform) {
            return array_map(static fn ($label) => $platform->quoteStringLiteral((string) $label), $this->labels);
        }

        $result = [];
        foreach ($this->labels as $label) {
            $result[] = $platform->quoteEnumLabel($label);
        }

        return $result;
    }
}
