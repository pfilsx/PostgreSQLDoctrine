<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tools;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Sequence;
use Doctrine\DBAL\Schema\Visitor\RemoveNamespacedAssets;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\Mapping\QuoteStrategy;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Doctrine\ORM\Tools\Event\GenerateSchemaTableEventArgs;
use Doctrine\ORM\Tools\Exception\MissingColumnException;
use Doctrine\ORM\Tools\Exception\NotSupported;
use Doctrine\ORM\Tools\SchemaTool as BaseTool;
use Doctrine\ORM\Tools\ToolEvents;
use Pfilsx\PostgreSQLDoctrine\DBAL\Schema\EnumTypeAsset;
use Pfilsx\PostgreSQLDoctrine\DBAL\Schema\EnumTypeUsageAsset;
use Pfilsx\PostgreSQLDoctrine\DBAL\Schema\Schema;
use Pfilsx\PostgreSQLDoctrine\DBAL\Schema\Table;
use Pfilsx\PostgreSQLDoctrine\DBAL\Type\EnumType;

final class SchemaTool extends BaseTool
{
    private const KNOWN_COLUMN_OPTIONS = ['comment', 'unsigned', 'fixed', 'default'];
    private AbstractSchemaManager $schemaManager;
    private EntityManagerInterface $em;
    private ?AbstractPlatform $platform;
    private QuoteStrategy $quoteStrategy;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
        $this->em = $em;
        $this->platform = $em->getConnection()->getDatabasePlatform();
        $this->quoteStrategy = $em->getConfiguration()->getQuoteStrategy();
        $this->schemaManager = method_exists(Connection::class, 'createSchemaManager')
            ? $em->getConnection()->createSchemaManager()
            : $em->getConnection()->getSchemaManager();
    }

    /**
     * @param ClassMetadata[] $classes
     *
     * @throws MappingException
     * @throws NotSupported
     * @throws SchemaException
     * @throws \Doctrine\DBAL\Exception
     *
     * @return Schema
     */
    public function getSchemaFromMetadata(array $classes): Schema
    {
        // Reminder for processed classes, used for hierarchies
        $processedClasses = [];
        $eventManager = $this->em->getEventManager();
        $metadataSchemaConfig = $this->schemaManager->createSchemaConfig();

        $schema = new Schema([], [], $metadataSchemaConfig);

        $addedFks = [];
        $blacklistedFks = [];

        foreach ($classes as $class) {
            if ($this->processingNotRequired($class, $processedClasses)) {
                continue;
            }

            $table = $schema->createTable($this->quoteStrategy->getTableName($class, $this->platform));

            if ($class->isInheritanceTypeSingleTable()) {
                $this->gatherColumns($class, $table);
                $this->gatherRelationsSql($class, $table, $schema, $addedFks, $blacklistedFks);

                // Add the discriminator column
                $this->addDiscriminatorColumnDefinition($class, $table);

                // Aggregate all the information from all classes in the hierarchy
                foreach ($class->parentClasses as $parentClassName) {
                    // Parent class information is already contained in this class
                    $processedClasses[$parentClassName] = true;
                }

                foreach ($class->subClasses as $subClassName) {
                    $subClass = $this->em->getClassMetadata($subClassName);
                    $this->gatherColumns($subClass, $table);
                    $this->gatherRelationsSql($subClass, $table, $schema, $addedFks, $blacklistedFks);
                    $processedClasses[$subClassName] = true;
                }
            } elseif ($class->isInheritanceTypeJoined()) {
                // Add all non-inherited fields as columns
                foreach ($class->fieldMappings as $fieldName => $mapping) {
                    if (!isset($mapping['inherited'])) {
                        $this->gatherColumn($class, $mapping, $table);
                    }
                }

                $this->gatherRelationsSql($class, $table, $schema, $addedFks, $blacklistedFks);

                // Add the discriminator column only to the root table
                if ($class->name === $class->rootEntityName) {
                    $this->addDiscriminatorColumnDefinition($class, $table);
                } else {
                    // Add an ID FK column to child tables
                    $pkColumns = [];
                    $inheritedKeyColumns = [];

                    foreach ($class->identifier as $identifierField) {
                        if (isset($class->fieldMappings[$identifierField]['inherited'])) {
                            $idMapping = $class->fieldMappings[$identifierField];
                            $this->gatherColumn($class, $idMapping, $table);
                            $columnName = $this->quoteStrategy->getColumnName(
                                $identifierField,
                                $class,
                                $this->platform
                            );
                            $table->getColumn($columnName)->setAutoincrement(false);

                            $pkColumns[] = $columnName;
                            $inheritedKeyColumns[] = $columnName;

                            continue;
                        }

                        if (isset($class->associationMappings[$identifierField]['inherited'])) {
                            $idMapping = $class->associationMappings[$identifierField];

                            $targetEntity = current(
                                array_filter(
                                    $classes,
                                    static function (ClassMetadata $class) use ($idMapping): bool {
                                        return $class->name === $idMapping['targetEntity'];
                                    }
                                )
                            );

                            foreach ($idMapping['joinColumns'] as $joinColumn) {
                                if (isset($targetEntity->fieldMappings[$joinColumn['referencedColumnName']])) {
                                    $columnName = $this->quoteStrategy->getJoinColumnName(
                                        $joinColumn,
                                        $class,
                                        $this->platform
                                    );

                                    $pkColumns[] = $columnName;
                                    $inheritedKeyColumns[] = $columnName;
                                }
                            }
                        }
                    }

                    if ($inheritedKeyColumns !== []) {
                        // Add a FK constraint on the ID column
                        $table->addForeignKeyConstraint(
                            $this->quoteStrategy->getTableName(
                                $this->em->getClassMetadata($class->rootEntityName),
                                $this->platform
                            ),
                            $inheritedKeyColumns,
                            $inheritedKeyColumns,
                            ['onDelete' => 'CASCADE']
                        );
                    }

                    if ($pkColumns !== []) {
                        $table->setPrimaryKey($pkColumns);
                    }
                }
            } elseif ($class->isInheritanceTypeTablePerClass()) {
                throw NotSupported::create();
            } else {
                $this->gatherColumns($class, $table);
                $this->gatherRelationsSql($class, $table, $schema, $addedFks, $blacklistedFks);
            }

            $pkColumns = [];

            foreach ($class->identifier as $identifierField) {
                if (isset($class->fieldMappings[$identifierField])) {
                    $pkColumns[] = $this->quoteStrategy->getColumnName($identifierField, $class, $this->platform);
                } elseif (isset($class->associationMappings[$identifierField])) {
                    $assoc = $class->associationMappings[$identifierField];
                    assert(is_array($assoc));

                    foreach ($assoc['joinColumns'] as $joinColumn) {
                        $pkColumns[] = $this->quoteStrategy->getJoinColumnName($joinColumn, $class, $this->platform);
                    }
                }
            }

            if (!$table->hasIndex('primary')) {
                $table->setPrimaryKey($pkColumns);
            }

            // there can be unique indexes automatically created for join column
            // if join column is also primary key we should keep only primary key on this column
            // so, remove indexes overruled by primary key
            $primaryKey = $table->getIndex('primary');

            foreach ($table->getIndexes() as $idxKey => $existingIndex) {
                if ($primaryKey->overrules($existingIndex)) {
                    $table->dropIndex($idxKey);
                }
            }

            if (isset($class->table['indexes'])) {
                foreach ($class->table['indexes'] as $indexName => $indexData) {
                    if (!isset($indexData['flags'])) {
                        $indexData['flags'] = [];
                    }

                    $table->addIndex(
                        $this->getIndexColumns($class, $indexData),
                        is_numeric($indexName) ? null : $indexName,
                        (array) $indexData['flags'],
                        $indexData['options'] ?? []
                    );
                }
            }

            if (isset($class->table['uniqueConstraints'])) {
                foreach ($class->table['uniqueConstraints'] as $indexName => $indexData) {
                    $uniqIndex = new Index('tmp__' . $indexName, $this->getIndexColumns($class, $indexData), true, false, [], $indexData['options'] ?? []);

                    foreach ($table->getIndexes() as $tableIndexName => $tableIndex) {
                        $method = method_exists($tableIndex, 'isFulfilledBy') ? 'isFulfilledBy' : 'isFullfilledBy';
                        if ($tableIndex->$method($uniqIndex)) {
                            $table->dropIndex($tableIndexName);

                            break;
                        }
                    }

                    $table->addUniqueIndex($uniqIndex->getColumns(), is_numeric($indexName) ? null : $indexName, $indexData['options'] ?? []);
                }
            }

            if (isset($class->table['options'])) {
                foreach ($class->table['options'] as $key => $val) {
                    $table->addOption($key, $val);
                }
            }

            $processedClasses[$class->name] = true;

            if ($class->isIdGeneratorSequence() && $class->name === $class->rootEntityName) {
                $seqDef = $class->sequenceGeneratorDefinition;
                $quotedName = $this->quoteStrategy->getSequenceName($seqDef, $class, $this->platform);
                if (!$schema->hasSequence($quotedName)) {
                    $schema->createSequence(
                        $quotedName,
                        (int) $seqDef['allocationSize'],
                        (int) $seqDef['initialValue']
                    );
                }
            }

            if ($eventManager->hasListeners(ToolEvents::postGenerateSchemaTable)) {
                $eventManager->dispatchEvent(
                    ToolEvents::postGenerateSchemaTable,
                    new GenerateSchemaTableEventArgs($class, $schema, $table)
                );
            }

            $this->gatherEnumTypes($class, $schema);
        }

        if (!$this->platform->supportsSchemas()) {
            $filter = /** @param Sequence|Table $asset */ static function ($asset) use ($schema): bool {
                return !$asset->isInDefaultNamespace($schema->getName());
            };

            if (array_filter($schema->getSequences() + $schema->getTables(), $filter) && !$this->platform->canEmulateSchemas()) {
                $schema->visit(new RemoveNamespacedAssets());
            }
        }

        // remove default namespace creation from down migration
        foreach ($this->schemaManager->getExistingSchemaSearchPaths() as $namespace) {
            if (!$schema->hasNamespace($namespace)) {
                $schema->createNamespace($namespace);
            }
        }

        if ($eventManager->hasListeners(ToolEvents::postGenerateSchema)) {
            $eventManager->dispatchEvent(
                ToolEvents::postGenerateSchema,
                new GenerateSchemaEventArgs($this->em, $schema)
            );
        }

        return $schema;
    }

    private function processingNotRequired(
        ClassMetadata $class,
        array $processedClasses
    ): bool {
        return isset($processedClasses[$class->name])
            || $class->isMappedSuperclass
            || $class->isEmbeddedClass
            || ($class->isInheritanceTypeSingleTable() && $class->name !== $class->rootEntityName)
            || in_array($class->name, $this->em->getConfiguration()->getSchemaIgnoreClasses());
    }

    private function gatherColumns(ClassMetadata $class, Table $table): void
    {
        $pkColumns = [];

        foreach ($class->fieldMappings as $mapping) {
            if ($class->isInheritanceTypeSingleTable() && isset($mapping['inherited'])) {
                continue;
            }

            $this->gatherColumn($class, $mapping, $table);

            if ($class->isIdentifier($mapping['fieldName'])) {
                $pkColumns[] = $this->quoteStrategy->getColumnName($mapping['fieldName'], $class, $this->platform);
            }
        }
    }

    /**
     * Creates a column definition as required by the DBAL from an ORM field mapping definition.
     *
     * @param ClassMetadata        $class   the class that owns the field mapping
     * @param array<string, mixed> $mapping the field mapping
     */
    private function gatherColumn(
        ClassMetadata $class,
        array $mapping,
        Table $table
    ): void {
        $columnName = $this->quoteStrategy->getColumnName($mapping['fieldName'], $class, $this->platform);
        $columnType = $mapping['type'];

        $options = [];
        $options['length'] = $mapping['length'] ?? null;
        $options['notnull'] = isset($mapping['nullable']) ? !$mapping['nullable'] : true;
        if ($class->isInheritanceTypeSingleTable() && $class->parentClasses) {
            $options['notnull'] = false;
        }

        $options['platformOptions'] = [];
        $options['platformOptions']['version'] = $class->isVersioned && $class->versionField === $mapping['fieldName'];

        if (strtolower($columnType) === 'string' && $options['length'] === null) {
            $options['length'] = 255;
        }

        if (isset($mapping['precision'])) {
            $options['precision'] = $mapping['precision'];
        }

        if (isset($mapping['scale'])) {
            $options['scale'] = $mapping['scale'];
        }

        if (isset($mapping['default'])) {
            $options['default'] = $mapping['default'];
        }

        if (isset($mapping['columnDefinition'])) {
            $options['columnDefinition'] = $mapping['columnDefinition'];
        }

        // the 'default' option can be overwritten here
        $options = $this->gatherColumnOptions($mapping) + $options;

        if ($class->isIdGeneratorIdentity() && $class->getIdentifierFieldNames() === [$mapping['fieldName']]) {
            $options['autoincrement'] = true;
        }

        if ($class->isInheritanceTypeJoined() && $class->name !== $class->rootEntityName) {
            $options['autoincrement'] = false;
        }

        if ($table->hasColumn($columnName)) {
            // required in some inheritance scenarios
            $table->modifyColumn($columnName, $options, $mapping['enumType'] ?? null);
        } else {
            $column = $table->addColumn($columnName, $columnType, $options);

            if (isset($mapping['enumType'])) {
                $column->setEnumClass($mapping['enumType']);
            }
        }

        $isUnique = $mapping['unique'] ?? false;
        if ($isUnique) {
            $table->addUniqueIndex([$columnName]);
        }
    }

    /**
     * @param mixed[] $mapping
     *
     * @return mixed[]
     */
    private function gatherColumnOptions(array $mapping): array
    {
        $mappingOptions = $mapping['options'] ?? [];

        if (isset($mapping['enumType'])) {
            $mappingOptions['enumType'] = $mapping['enumType'];
        }

        if (($mappingOptions['default'] ?? null) instanceof \BackedEnum) {
            $mappingOptions['default'] = $mappingOptions['default']->value;
        }

        if (empty($mappingOptions)) {
            return [];
        }

        $options = array_intersect_key($mappingOptions, array_flip(self::KNOWN_COLUMN_OPTIONS));
        $options['platformOptions'] = array_diff_key($mappingOptions, $options);

        return $options;
    }

    /**
     * Gathers the SQL for properly setting up the relations of the given class.
     * This includes the SQL for foreign key constraints and join tables.
     *
     * @psalm-param array<string, array{
     *                  foreignTableName: string,
     *                  foreignColumns: list<string>
     *              }>                               $addedFks
     * @psalm-param array<string, bool>              $blacklistedFks
     *
     * @throws NotSupported
     */
    private function gatherRelationsSql(
        ClassMetadata $class,
        Table $table,
        Schema $schema,
        array &$addedFks,
        array &$blacklistedFks
    ): void {
        foreach ($class->associationMappings as $id => $mapping) {
            if (isset($mapping['inherited']) && !in_array($id, $class->identifier, true)) {
                continue;
            }

            $foreignClass = $this->em->getClassMetadata($mapping['targetEntity']);

            if ($mapping['type'] & ClassMetadata::TO_ONE && $mapping['isOwningSide']) {
                $primaryKeyColumns = []; // PK is unnecessary for this relation-type

                $this->gatherRelationJoinColumns(
                    $mapping['joinColumns'],
                    $table,
                    $foreignClass,
                    $mapping,
                    $primaryKeyColumns,
                    $addedFks,
                    $blacklistedFks
                );
            } elseif ($mapping['type'] === ClassMetadata::ONE_TO_MANY && $mapping['isOwningSide']) {
                // ... create join table, one-many through join table supported later
                throw NotSupported::create();
            } elseif ($mapping['type'] === ClassMetadata::MANY_TO_MANY && $mapping['isOwningSide']) {
                // create join table
                $joinTable = $mapping['joinTable'];

                $theJoinTable = $schema->createTable(
                    $this->quoteStrategy->getJoinTableName($mapping, $foreignClass, $this->platform)
                );

                if (isset($joinTable['options'])) {
                    foreach ($joinTable['options'] as $key => $val) {
                        $theJoinTable->addOption($key, $val);
                    }
                }

                $primaryKeyColumns = [];

                // Build first FK constraint (relation table => source table)
                $this->gatherRelationJoinColumns(
                    $joinTable['joinColumns'],
                    $theJoinTable,
                    $class,
                    $mapping,
                    $primaryKeyColumns,
                    $addedFks,
                    $blacklistedFks
                );

                // Build second FK constraint (relation table => target table)
                $this->gatherRelationJoinColumns(
                    $joinTable['inverseJoinColumns'],
                    $theJoinTable,
                    $foreignClass,
                    $mapping,
                    $primaryKeyColumns,
                    $addedFks,
                    $blacklistedFks
                );

                $theJoinTable->setPrimaryKey($primaryKeyColumns);
            }
        }
    }

    /**
     * Gathers columns and fk constraints that are required for one part of relationship.
     *
     * @psalm-param array<string, mixed>             $joinColumns
     * @psalm-param array<string, mixed>             $mapping
     * @psalm-param list<string>                     $primaryKeyColumns
     * @psalm-param array<string, array{
     *                  foreignTableName: string,
     *                  foreignColumns: list<string>
     *              }>                               $addedFks
     * @psalm-param array<string,bool>               $blacklistedFks
     *
     * @throws MissingColumnException
     */
    private function gatherRelationJoinColumns(
        array $joinColumns,
        Table $theJoinTable,
        ClassMetadata $class,
        array $mapping,
        array &$primaryKeyColumns,
        array &$addedFks,
        array &$blacklistedFks
    ): void {
        $localColumns = [];
        $foreignColumns = [];
        $fkOptions = [];
        $foreignTableName = $this->quoteStrategy->getTableName($class, $this->platform);
        $uniqueConstraints = [];

        foreach ($joinColumns as $joinColumn) {
            [$definingClass, $referencedFieldName] = $this->getDefiningClass(
                $class,
                $joinColumn['referencedColumnName']
            );

            if (!$definingClass) {
                throw MissingColumnException::fromColumnSourceAndTarget($joinColumn['referencedColumnName'], $mapping['sourceEntity'], $mapping['targetEntity']);
            }

            $quotedColumnName = $this->quoteStrategy->getJoinColumnName($joinColumn, $class, $this->platform);
            $quotedRefColumnName = $this->quoteStrategy->getReferencedJoinColumnName(
                $joinColumn,
                $class,
                $this->platform
            );

            $primaryKeyColumns[] = $quotedColumnName;
            $localColumns[] = $quotedColumnName;
            $foreignColumns[] = $quotedRefColumnName;

            if (!$theJoinTable->hasColumn($quotedColumnName)) {
                // Only add the column to the table if it does not exist already.
                // It might exist already if the foreign key is mapped into a regular
                // property as well.

                $fieldMapping = $definingClass->getFieldMapping($referencedFieldName);

                $columnOptions = ['notnull' => false];

                if (isset($joinColumn['columnDefinition'])) {
                    $columnOptions['columnDefinition'] = $joinColumn['columnDefinition'];
                } elseif (isset($fieldMapping['columnDefinition'])) {
                    $columnOptions['columnDefinition'] = $fieldMapping['columnDefinition'];
                }

                if (isset($joinColumn['nullable'])) {
                    $columnOptions['notnull'] = !$joinColumn['nullable'];
                }

                $columnOptions += $this->gatherColumnOptions($fieldMapping);

                if (isset($fieldMapping['length'])) {
                    $columnOptions['length'] = $fieldMapping['length'];
                }

                if ($fieldMapping['type'] === 'decimal') {
                    $columnOptions['scale'] = $fieldMapping['scale'];
                    $columnOptions['precision'] = $fieldMapping['precision'];
                }

                $columnOptions = $this->gatherColumnOptions($joinColumn) + $columnOptions;

                $theJoinTable->addColumn($quotedColumnName, $fieldMapping['type'], $columnOptions);
            }

            if (isset($joinColumn['unique']) && $joinColumn['unique'] === true) {
                $uniqueConstraints[] = ['columns' => [$quotedColumnName]];
            }

            if (isset($joinColumn['onDelete'])) {
                $fkOptions['onDelete'] = $joinColumn['onDelete'];
            }
        }

        // Prefer unique constraints over implicit simple indexes created for foreign keys.
        // Also avoids index duplication.
        foreach ($uniqueConstraints as $indexName => $unique) {
            $theJoinTable->addUniqueIndex($unique['columns'], is_numeric($indexName) ? null : $indexName);
        }

        $compositeName = $theJoinTable->getName() . '.' . implode('', $localColumns);
        if (
            isset($addedFks[$compositeName])
            && ($foreignTableName !== $addedFks[$compositeName]['foreignTableName']
                || count(array_diff($foreignColumns, $addedFks[$compositeName]['foreignColumns'])) > 0)
        ) {
            foreach ($theJoinTable->getForeignKeys() as $fkName => $key) {
                if (
                    count(array_diff($key->getLocalColumns(), $localColumns)) === 0
                    && (($key->getForeignTableName() !== $foreignTableName)
                        || count(array_diff($key->getForeignColumns(), $foreignColumns)) > 0)
                ) {
                    $theJoinTable->removeForeignKey($fkName);

                    break;
                }
            }

            $blacklistedFks[$compositeName] = true;
        } elseif (!isset($blacklistedFks[$compositeName])) {
            $addedFks[$compositeName] = ['foreignTableName' => $foreignTableName, 'foreignColumns' => $foreignColumns];
            $theJoinTable->addForeignKeyConstraint(
                $foreignTableName,
                $localColumns,
                $foreignColumns,
                $fkOptions
            );
        }
    }

    /**
     * Gets the class metadata that is responsible for the definition of the referenced column name.
     *
     * Previously this was a simple task, but with DDC-117 this problem is actually recursive. If its
     * not a simple field, go through all identifier field names that are associations recursively and
     * find that referenced column name.
     */
    private function getDefiningClass(ClassMetadata $class, string $referencedColumnName): ?array
    {
        $referencedFieldName = $class->getFieldName($referencedColumnName);

        if ($class->hasField($referencedFieldName)) {
            return [$class, $referencedFieldName];
        }

        if (in_array($referencedColumnName, $class->getIdentifierColumnNames(), true)) {
            // it seems to be an entity as foreign key
            foreach ($class->getIdentifierFieldNames() as $fieldName) {
                if (
                    $class->hasAssociation($fieldName)
                    && $class->getSingleAssociationJoinColumnName($fieldName) === $referencedColumnName
                ) {
                    return $this->getDefiningClass(
                        $this->em->getClassMetadata($class->associationMappings[$fieldName]['targetEntity']),
                        $class->getSingleAssociationReferencedJoinColumnName($fieldName)
                    );
                }
            }
        }

        return null;
    }

    /**
     * Gets a portable column definition as required by the DBAL for the discriminator
     * column of a class.
     */
    private function addDiscriminatorColumnDefinition(ClassMetadata $class, Table $table): void
    {
        $discrColumn = $class->discriminatorColumn;

        if (
            !isset($discrColumn['type'])
            || (strtolower($discrColumn['type']) === 'string' && !isset($discrColumn['length']))
        ) {
            $discrColumn['type'] = 'string';
            $discrColumn['length'] = 255;
        }

        $options = [
            'length' => $discrColumn['length'] ?? null,
            'notnull' => true,
        ];

        if (isset($discrColumn['columnDefinition'])) {
            $options['columnDefinition'] = $discrColumn['columnDefinition'];
        }

        $options = $this->gatherColumnOptions($discrColumn) + $options;
        $table->addColumn($discrColumn['name'], $discrColumn['type'], $options);
    }

    /**
     * Resolves fields in index mapping to column names.
     *
     * @param mixed[] $indexData index or unique constraint data
     *
     * @return string[] Column names from combined fields and columns mappings
     */
    private function getIndexColumns(ClassMetadata $class, array $indexData): array
    {
        $columns = [];

        if (
            isset($indexData['columns'], $indexData['fields'])
            || (
                !isset($indexData['columns'])
                && !isset($indexData['fields'])
            )
        ) {
            throw MappingException::invalidIndexConfiguration($class, $indexData['name'] ?? 'unnamed');
        }

        if (isset($indexData['columns'])) {
            $columns = $indexData['columns'];
        }

        if (isset($indexData['fields'])) {
            foreach ($indexData['fields'] as $fieldName) {
                if ($class->hasField($fieldName)) {
                    $columns[] = $this->quoteStrategy->getColumnName($fieldName, $class, $this->platform);
                } elseif ($class->hasAssociation($fieldName)) {
                    foreach ($class->getAssociationMapping($fieldName)['joinColumns'] as $joinColumn) {
                        $columns[] = $this->quoteStrategy->getJoinColumnName($joinColumn, $class, $this->platform);
                    }
                }
            }
        }

        return $columns;
    }

    private function gatherEnumTypes(ClassMetadata $metadata, Schema $schema): void
    {
        foreach ($metadata->fieldMappings as $field) {
            if ($field['type'] !== EnumType::NAME) {
                continue;
            }
            $enumTypeClass = $field['enumType'] ?? null;

            if ($enumTypeClass === null || !\class_exists($enumTypeClass)) {
                throw new SchemaException('The option "enumType" has to be specified and has to be a real fully qualified class name.');
            }

            $enumName = EnumTool::getEnumTypeNameFromClassName($enumTypeClass);

            $enumType = $schema->hasEnumType($enumName)
                ? $schema->getEnumType($enumName)
                : EnumTypeAsset::fromEnumClassName($enumName, $enumTypeClass)
            ;

            $rawDefault = ($field['options'] ?? [])['default'] ?? null;

            if ($rawDefault instanceof \BackedEnum) {
                $default = $rawDefault->value;
            } elseif ($rawDefault instanceof \UnitEnum) {
                $default = $rawDefault->name;
            } else {
                $default = $rawDefault;
            }

            $enumType->addUsage(
                new EnumTypeUsageAsset(
                    $metadata->getTableName(),
                    $field['columnName'],
                    $default
                )
            );

            if (!$schema->hasEnumType($enumType->getName())) {
                $schema->addEnumType($enumName, $enumType);
            }
        }
    }
}
