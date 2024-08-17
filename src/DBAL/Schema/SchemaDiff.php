<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Schema;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaDiff as BaseSchemaDiff;
use Pfilsx\PostgreSQLDoctrine\DBAL\Platform\PostgreSQLPlatform;

final class SchemaDiff extends BaseSchemaDiff
{
    private array $createdTypes;

    private array $alteredTypes;

    private array $droppedTypes;

    public function __construct(
        array $createdTables = [],
        array $alteredTables = [],
        array $droppedTables = [],
        ?Schema $fromSchema = null,
        array $createdSchemas = [],
        array $droppedSchemas = [],
        array $createdSequences = [],
        array $alteredSequences = [],
        array $droppedSequences = [],
        array $createdTypes = [],
        array $alteredTypes = [],
        array $droppedTypes = []
    ) {
        parent::__construct(
            $createdTables,
            $alteredTables,
            $droppedTables,
            $fromSchema,
            $createdSchemas,
            $droppedSchemas,
            $createdSequences,
            $alteredSequences,
            $droppedSequences,
        );

        $this->createdTypes = $createdTypes;
        $this->alteredTypes = $alteredTypes;
        $this->droppedTypes = $droppedTypes;
    }

    /**
     * @param AbstractPlatform $platform
     *
     * @return string[]
     */
    public function toSql(AbstractPlatform $platform): array
    {
        if (!$platform instanceof PostgreSQLPlatform) {
            throw new InvalidArgumentException('Option \'platform\' must be a subtype of \'' . PostgreSQLPlatform::class . '\', instance of \'' . \get_class($platform) . '\' given');
        }
        $sql = [];

        foreach ($this->getCreatedTypes() as $type) {
            $sql[] = $platform->getCreateTypeSql($type);
            $sql[] = $platform->getCommentOnTypeSql($type);
        }

        foreach ($this->getAlteredTypes() as $alterTypeArray) {
            $sql = array_merge(
                $sql,
                $platform->getAlterTypeSql($alterTypeArray['from'], $alterTypeArray['to'])
            );
        }

        $sql = \array_merge($sql, $this->_toSql($platform, false));

        foreach ($this->getDroppedTypes() as $type) {
            $sql[] = $platform->getDropTypeSql($type);
        }

        return $sql;
    }

    public function getCreatedTypes(): array
    {
        return $this->createdTypes;
    }

    public function getAlteredTypes(): array
    {
        return $this->alteredTypes;
    }

    public function getDroppedTypes(): array
    {
        return $this->droppedTypes;
    }
}
