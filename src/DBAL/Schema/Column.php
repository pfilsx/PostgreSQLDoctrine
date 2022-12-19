<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Schema;

final class Column extends \Doctrine\DBAL\Schema\Column
{
    private ?string $_enumClass = null;

    public function getEnumClass(): ?string
    {
        return $this->_enumClass;
    }

    public function setEnumClass(?string $enumClass): self
    {
        $this->_enumClass = $enumClass;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_merge([
            'name' => $this->_name,
            'type' => $this->_type,
            'default' => $this->_default,
            'notnull' => $this->_notnull,
            'length' => $this->_length,
            'precision' => $this->_precision,
            'scale' => $this->_scale,
            'fixed' => $this->_fixed,
            'unsigned' => $this->_unsigned,
            'autoincrement' => $this->_autoincrement,
            'columnDefinition' => $this->_columnDefinition,
            'comment' => $this->_comment,
            'enumType' => $this->_enumClass,
        ], $this->_platformOptions, $this->_customSchemaOptions);
    }
}