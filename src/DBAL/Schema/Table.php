<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Schema;



use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table as BaseTable;
use Doctrine\DBAL\Types\Type;

final class Table extends BaseTable
{
    public function addColumn($name, $typeName, array $options = []): Column
    {
        $column = new Column($name, Type::getType($typeName), $options);

        $this->_addColumn($column);

        return $column;
    }

    /**
     * @param string  $name
     * @param mixed[] $options
     *
     * @return self
     *
     * @throws SchemaException
     */
    public function modifyColumn($name, array $options, ?string $enumClass = null): self
    {
        $column = $this->getColumn($name);
        $column->setOptions($options);
        $column->setEnumClass($enumClass);

        return $this;
    }
}