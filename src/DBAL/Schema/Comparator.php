<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\DBAL\Schema;

use Doctrine\DBAL\Schema\Comparator as BaseComparator;
use Doctrine\DBAL\Schema\Schema as BaseSchema;
use Doctrine\DBAL\Schema\SchemaDiff as BaseSchemaDiff;

final class Comparator extends BaseComparator
{
    public function compareSchemas(BaseSchema $fromSchema, BaseSchema $toSchema): BaseSchemaDiff
    {
        $baseDiff = parent::compareSchemas($fromSchema, $toSchema);

        if (!$fromSchema instanceof Schema || !$toSchema instanceof Schema) {
            return $baseDiff;
        }

        $createdTypes = [];
        $alteredTypes = [];
        $droppedTypes = [];

        foreach ($toSchema->getEnumTypes() as $type) {
            $typeName = $type->getShortestName($toSchema->getName());
            if (!$fromSchema->hasEnumType($typeName)) {
                $createdTypes[] = $type;
            } else {
                if ($type->getLabels() !== ($fromType = $fromSchema->getEnumType($typeName))->getLabels()) {
                    $alteredTypes[] = ['from' => $fromType, 'to' => $type];
                }
            }
        }

        foreach ($fromSchema->getEnumTypes() as $type) {
            $typeName = $type->getShortestName($fromSchema->getName());

            if ($toSchema->hasEnumType($typeName)) {
                continue;
            }

            $droppedTypes[] = $type;
        }

        $diff = new SchemaDiff(
            $baseDiff->getCreatedTables(),
            $baseDiff->getAlteredTables(),
            $baseDiff->getDroppedTables(),
            $baseDiff->fromSchema,
            $baseDiff->getCreatedSchemas(),
            $baseDiff->getDroppedSchemas(),
            $baseDiff->getCreatedSequences(),
            $baseDiff->getAlteredSequences(),
            $baseDiff->getDroppedSequences(),
            $createdTypes,
            $alteredTypes,
            $droppedTypes
        );

        $diff->orphanedForeignKeys = $baseDiff->orphanedForeignKeys;

        return $diff;
    }
}