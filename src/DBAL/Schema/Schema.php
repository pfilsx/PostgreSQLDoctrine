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
    private array $_enumTypes = [];

    public function __construct(
        array $tables = [],
        array $sequences = [],
        ?SchemaConfig $schemaConfig = null,
        array $namespaces = [],
        array $types = []
    ) {
        parent::__construct($tables, $sequences, $schemaConfig, $namespaces);

        foreach ($types as $type) {
            $this->addEnumType($type->getName(), $type);
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
        return $this->_enumTypes;
    }

    public function getEnumType(string $name): EnumTypeAsset
    {
        $name = $this->getFullQualifiedAssetName($name);
        if (!$this->hasEnumType($name)) {
            throw new SchemaException("There exists no enum type with the name \"$name\".");
        }

        return $this->_enumTypes[$name];
    }

    public function hasEnumType(string $name): bool
    {
        $name = $this->getFullQualifiedAssetName($name);

        return isset($this->_enumTypes[$name]);
    }

    public function addEnumType(string $name, EnumTypeAsset $type): void
    {
        $typeName = $this->getFullQualifiedAssetName($name);

        if (isset($this->_enumTypes[$typeName])) {
            throw new SchemaException("The enum type \"$typeName\" already exists.");
        }

        $this->_enumTypes[$typeName] = $type;
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