<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\ORM\Trait;

use Doctrine\DBAL\Exception;

trait ExistsMethodRepositoryTrait
{
    /**
     * @param array<string, mixed> $predicates
     *
     * @throws Exception
     *
     * @return bool
     */
    public function exists(array $predicates): bool
    {
        $subQb = $this->createQueryBuilder('_sub')->select('1');

        $parameters = [];
        $idx = 1;
        foreach ($predicates as $field => $value) {
            if (\is_array($value)) {
                $subQb->andWhere($subQb->expr()->in("_sub.$field", $value));

                continue;
            }

            if (\is_null($value)) {
                $subQb->andWhere($subQb->expr()->isNull("_sub.$field"));

                continue;
            }

            if (\is_object($value)) {
                if (!\method_exists($value, 'getId')) {
                    throw new \RuntimeException("Unable to cast object predicate value to scalar for key: $field");
                }

                $value = $value->getId();
            }

            $subQb->andWhere("_sub.$field = ?$idx");
            $parameters[$idx] = $value;
            ++$idx;
        }

        return (bool) $this->_em
            ->getConnection()
            ->createQueryBuilder()
            ->select("EXISTS({$subQb->getQuery()->getSQL()})")
            ->setParameters($parameters)
            ->fetchOne()
        ;
    }
}
