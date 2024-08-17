<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Schema;

use Doctrine\DBAL\Result;
use Doctrine\DBAL\Schema\Identifier;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Doctrine\Deprecations\Deprecation;
use Pfilsx\PostgreSQLDoctrine\DBAL\Platform\PostgreSQLPlatform;

class PostgreSQLSchemaManager extends \Doctrine\DBAL\Schema\PostgreSQLSchemaManager
{
    /**
     * @var PostgreSQLPlatform
     */
    protected $_platform;

    public function createSchema(): Schema
    {
        $schemaNames = [];

        if ($this->_platform->supportsSchemas()) {
            $schemaNames = $this->listNamespaceNames();
        }

        $sequences = [];

        if ($this->_platform->supportsSequences()) {
            $sequences = $this->listSequences();
        }

        $enumTypes = [];

        if ($this->_platform instanceof PostgreSQLPlatform) {
            $enumTypes = $this->listEnumTypes();
        }

        $tables = $this->listTables();

        return new Schema($tables, $sequences, $this->createSchemaConfig(), $schemaNames, $enumTypes);
    }

    /**
     * @return EnumTypeAsset[]
     */
    public function listEnumTypes(): array
    {
        $sql = $this->_platform->getListEnumTypesSQL();

        $types = $this->_conn->fetchAllAssociative($sql);

        return $this->filterAssetNames($this->getPortableEnumTypesList($types));
    }

    public function createComparator(): Comparator
    {
        return new Comparator($this->_platform);
    }

    protected function selectTableColumns(string $databaseName, ?string $tableName = null): Result
    {
        $sql = 'SELECT';

        if ($tableName === null) {
            $sql .= ' c.relname AS table_name, n.nspname AS schema_name,';
        }

        $sql .= <<<'SQL'
            a.attnum,
            quote_ident(a.attname) AS field,
            t.typname AS type,
            format_type(a.atttypid, a.atttypmod) AS complete_type,
            (SELECT tc.collcollate FROM pg_catalog.pg_collation tc WHERE tc.oid = a.attcollation) AS collation,
            (SELECT t1.typname FROM pg_catalog.pg_type t1 WHERE t1.oid = t.typbasetype) AS domain_type,
            (SELECT format_type(t2.typbasetype, t2.typtypmod) FROM
              pg_catalog.pg_type t2 WHERE t2.typtype = 'd' AND t2.oid = a.atttypid) AS domain_complete_type,
            a.attnotnull AS isnotnull,
            (SELECT 't'
             FROM pg_index
             WHERE c.oid = pg_index.indrelid
                AND pg_index.indkey[0] = a.attnum
                AND pg_index.indisprimary = 't'
            ) AS pri,
            (SELECT pg_get_expr(adbin, adrelid)
             FROM pg_attrdef
             WHERE c.oid = pg_attrdef.adrelid
                AND pg_attrdef.adnum=a.attnum
            ) AS default,
            (SELECT pg_description.description
                FROM pg_description WHERE pg_description.objoid = c.oid AND a.attnum = pg_description.objsubid
            ) AS comment,
            (SELECT EXISTS(SELECT 1 FROM pg_enum WHERE pg_enum.enumtypid = t.oid)) AS is_enum,
            (SELECT pg_description.description FROM pg_description WHERE pg_description.objoid = t.oid) AS type_comment
            FROM pg_attribute a
                INNER JOIN pg_class c
                    ON c.oid = a.attrelid
                INNER JOIN pg_type t
                    ON t.oid = a.atttypid
                INNER JOIN pg_namespace n
                    ON n.oid = c.relnamespace
                LEFT JOIN pg_depend d
                    ON d.objid = c.oid
                        AND d.deptype = 'e'
SQL;

        $conditions = array_merge([
            'a.attnum > 0',
            "c.relkind = 'r'",
            'd.refobjid IS NULL',
        ], $this->buildQueryConditions($tableName));

        $sql .= ' WHERE ' . implode(' AND ', $conditions) . ' ORDER BY a.attnum';

        return $this->_conn->executeQuery($sql);
    }

    protected function _getPortableTableColumnDefinition($tableColumn): Column
    {
        $tableColumn = array_change_key_case($tableColumn, CASE_LOWER);

        if (strtolower($tableColumn['type']) === 'varchar' || strtolower($tableColumn['type']) === 'bpchar') {
            // get length from varchar definition
            $length = preg_replace('~.*\(([0-9]*)\).*~', '$1', $tableColumn['complete_type']);
            $tableColumn['length'] = $length;
        }

        $matches = [];

        $autoincrement = false;

        if (
            $tableColumn['default'] !== null
            && preg_match("/^nextval\('(.*)'(::.*)?\)$/", $tableColumn['default'], $matches) === 1
        ) {
            $tableColumn['sequence'] = $matches[1];
            $tableColumn['default'] = null;
            $autoincrement = true;
        }

        if ($tableColumn['default'] !== null) {
            if (preg_match("/^['(](.*)[')]::/", $tableColumn['default'], $matches) === 1) {
                $tableColumn['default'] = $matches[1];
            } elseif (preg_match('/^NULL::/', $tableColumn['default']) === 1) {
                $tableColumn['default'] = null;
            }
        }

        $length = $tableColumn['length'] ?? null;
        if ($length === '-1' && isset($tableColumn['atttypmod'])) {
            $length = $tableColumn['atttypmod'] - 4;
        }

        if ((int) $length <= 0) {
            $length = null;
        }

        $fixed = null;

        if (!isset($tableColumn['name'])) {
            $tableColumn['name'] = '';
        }

        if ($tableColumn['is_enum'] ?? false) {
            $tableColumn['type'] = 'enum';
        }

        $precision = null;
        $scale = null;
        $jsonb = null;

        $dbType = strtolower($tableColumn['type']);
        if (
            $tableColumn['domain_type'] !== null
            && $tableColumn['domain_type'] !== ''
            && !$this->_platform->hasDoctrineTypeMappingFor($tableColumn['type'])
        ) {
            $dbType = strtolower($tableColumn['domain_type']);
            $tableColumn['complete_type'] = $tableColumn['domain_complete_type'];
        }

        $type = $this->_platform->getDoctrineTypeMapping($dbType);
        $type = $this->extractDoctrineTypeFromComment($tableColumn['comment'], $type);
        $tableColumn['comment'] = $this->removeDoctrineTypeFromComment($tableColumn['comment'], $type);

        switch ($dbType) {
            case 'smallint':
            case 'bigint':
            case 'int':
            case 'int2':
            case 'int4':
            case 'int8':
            case 'integer':
                $tableColumn['default'] = $this->fixVersion94NegativeNumericDefaultValue($tableColumn['default']);
                $length = null;

                break;

            case 'bool':
            case 'boolean':
                if ($tableColumn['default'] === 'true') {
                    $tableColumn['default'] = true;
                }

                if ($tableColumn['default'] === 'false') {
                    $tableColumn['default'] = false;
                }

                $length = null;

                break;

            case 'text':
            case '_varchar':
            case 'varchar':
                $tableColumn['default'] = $this->parseDefaultExpression($tableColumn['default']);
                $fixed = false;

                break;
            case 'interval':
                $fixed = false;

                break;

            case 'char':
            case 'bpchar':
                $fixed = true;

                break;

            case 'float':
            case 'float4':
            case 'float8':
            case 'double':
            case 'double precision':
            case 'real':
            case 'decimal':
            case 'money':
            case 'numeric':
                $tableColumn['default'] = $this->fixVersion94NegativeNumericDefaultValue($tableColumn['default']);

                if (
                    preg_match(
                        '([A-Za-z]+\(([0-9]+),([0-9]+)\))',
                        $tableColumn['complete_type'],
                        $match,
                    ) === 1
                ) {
                    $precision = $match[1];
                    $scale = $match[2];
                    $length = null;
                }

                break;

            case 'year':
                $length = null;

                break;

                // PostgreSQL 9.4+ only
            case 'jsonb':
                $jsonb = true;

                break;
        }

        if (
            $tableColumn['default'] !== null && preg_match(
                "('([^']+)'::)",
                (string) $tableColumn['default'],
                $match,
            ) === 1
        ) {
            $tableColumn['default'] = $match[1];
        }

        $options = [
            'length' => $length,
            'notnull' => (bool) $tableColumn['isnotnull'],
            'default' => $tableColumn['default'],
            'precision' => $precision,
            'scale' => $scale,
            'fixed' => $fixed,
            'unsigned' => false,
            'autoincrement' => $autoincrement,
            'comment' => isset($tableColumn['comment']) && $tableColumn['comment'] !== ''
                ? $tableColumn['comment']
                : null,
        ];

        $column = new Column($tableColumn['field'], Type::getType($type), $options);

        if ($tableColumn['is_enum']) {
            $enumClassName = $tableColumn['type_comment'] ?? null;

            if ($enumClassName !== null && class_exists($enumClassName)) {
                $column->setEnumClass($enumClassName);
            }
        }

        if (isset($tableColumn['collation']) && !empty($tableColumn['collation'])) {
            $column->setPlatformOption('collation', $tableColumn['collation']);
        }

        if ($column->getType()->getName() === Types::JSON) {
            if (!$column->getType() instanceof JsonType) {
                Deprecation::trigger(
                    'doctrine/dbal',
                    'https://github.com/doctrine/dbal/pull/5049',
                    <<<'DEPRECATION'
                    %s not extending %s while being named %s is deprecated,
                    and will lead to jsonb never to being used in 4.0.,
                    DEPRECATION,
                    get_class($column->getType()),
                    JsonType::class,
                    Types::JSON,
                );
            }

            $column->setPlatformOption('jsonb', $jsonb);
        }

        return $column;
    }

    private function getPortableEnumTypesList(array $rawTypes): array
    {
        $list = [];
        foreach ($rawTypes as $rawType) {
            $labels = json_decode($rawType['labels'], true);
            usort($labels, static fn (array $a, array $b) => $a['order'] <=> $b['order']);

            $usages = json_decode($rawType['usages'], true);
            foreach ($usages as &$usage) {
                $default = $usage['default'] ?? null;
                if ($default !== null) {
                    $default = trim(explode('::', $default)[0], '\'');
                }
                $usage['default'] = $default;
            }

            $list[] = new EnumTypeAsset(
                $rawType['name'],
                $rawType['comment'],
                array_column($labels, 'label'),
                array_map(
                    static fn (array $usage) => new EnumTypeUsageAsset($usage['table'], $usage['column'], $usage['default']),
                    $usages
                )
            );
        }

        return $list;
    }

    private function buildQueryConditions($tableName): array
    {
        $conditions = [];

        if ($tableName !== null) {
            if (str_contains($tableName, '.')) {
                [$schemaName, $tableName] = explode('.', $tableName);
                $conditions[] = 'n.nspname = ' . $this->_platform->quoteStringLiteral($schemaName);
            } else {
                $conditions[] = 'n.nspname = ANY(current_schemas(false))';
            }

            $identifier = new Identifier($tableName);
            $conditions[] = 'c.relname = ' . $this->_platform->quoteStringLiteral($identifier->getName());
        }

        $conditions[] = "n.nspname NOT IN ('pg_catalog', 'information_schema', 'pg_toast')";

        return $conditions;
    }

    /**
     * PostgreSQL 9.4 puts parentheses around negative numeric default values that need to be stripped eventually.
     */
    private function fixVersion94NegativeNumericDefaultValue(mixed $defaultValue): mixed
    {
        if ($defaultValue !== null && str_starts_with($defaultValue, '(')) {
            return trim($defaultValue, '()');
        }

        return $defaultValue;
    }

    /**
     * Parses a default value expression as given by PostgreSQL.
     */
    private function parseDefaultExpression(?string $default): ?string
    {
        if ($default === null) {
            return null;
        }

        return str_replace("''", "'", $default);
    }
}
