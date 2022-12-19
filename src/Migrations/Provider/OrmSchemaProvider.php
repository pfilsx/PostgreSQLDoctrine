<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Migrations\Provider;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\Configuration\EntityManager\EntityManagerLoader;
use Doctrine\Migrations\Provider\Exception\NoMappingFound;
use Doctrine\Migrations\Provider\SchemaProvider as BaseSchemaProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Pfilsx\PostgreSQLDoctrine\Tools\SchemaTool;

final class OrmSchemaProvider implements BaseSchemaProvider
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerLoader $emLoader)
    {
        $this->entityManager = $emLoader->getEntityManager();
    }

    public function createSchema(): Schema
    {
        /** @var array<int, ClassMetadata<object>> $metadata */
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        if (\count($metadata) === 0) {
            throw NoMappingFound::new();
        }

        \usort($metadata, static function (ClassMetadata $a, ClassMetadata $b): int {
            return $a->getTableName() <=> $b->getTableName();
        });

        $tool = new SchemaTool($this->entityManager);

        return $tool->getSchemaFromMetadata($metadata);
    }
}
