<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Schema;

use Doctrine\DBAL\Schema\Schema as BaseSchema;
use Doctrine\DBAL\Schema\SchemaConfig;
use Doctrine\DBAL\Schema\SchemaException;

final class Schema extends BaseSchema
{
    /**
     * @var EnumTypeAsset[]
     */
    private array $enumTypes = [];

    public function __construct(
        array $tables = [],
        array $sequences = [],
        ?SchemaConfig $schemaConfig = null,
        array $namespaces = [],
        array $types = []
    ) {
        parent::__construct($tables, $sequences, $schemaConfig, $namespaces);

        foreach ($types as $type) {
            $this->addEnumType($type);
        }
    }

    public function createTable($name): Table
    {
        $table = new Table($name);
        $this->_addTable($table);

        foreach ($this->_schemaConfig->getDefaultTableOptions() as $option => $value) {
            $table->addOption($option, $value);
        }

        return $table;
    }

    /**
     * @return EnumTypeAsset[]
     */
    public function getEnumTypes(): array
    {
        return $this->enumTypes;
    }

    public function getEnumType(string $name): EnumTypeAsset
    {
        $name = $this->getFullQualifiedAssetName($name);
        if (!$this->hasEnumType($name)) {
            throw new SchemaException("There exists no enum type with the name \"$name\".");
        }

        return $this->enumTypes[$name];
    }

    public function hasEnumType(string $name): bool
    {
        $name = $this->getFullQualifiedAssetName($name);

        return isset($this->enumTypes[$name]);
    }

    public function addEnumType(EnumTypeAsset $type): void
    {
        $typeName = $type->getFullQualifiedName($this->getName());

        if (isset($this->enumTypes[$typeName])) {
            throw new SchemaException("The enum type \"$typeName\" already exists.");
        }

        $this->enumTypes[$typeName] = $type;
    }

    private function getFullQualifiedAssetName($name): string
    {
        $name = $this->getUnquotedAssetName($name);

        if (!\str_contains($name, '.')) {
            $name = $this->getName() . '.' . $name;
        }

        return \mb_strtolower($name);
    }

    private function getUnquotedAssetName($assetName): string
    {
        if ($this->isIdentifierQuoted($assetName)) {
            return $this->trimQuotes($assetName);
        }

        return $assetName;
    }
}