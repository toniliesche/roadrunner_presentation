<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Database\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use ToniLiesche\Roadrunner\Infrastructure\Database\Shared\EntityClassDoesNotExistException;

trait OrmAwareTrait
{
    private readonly EntityManagerInterface $entityManager;

    /**
     * @template T
     * @param class-string<T> $entityClass
     *
     * @return EntityRepository<T>
     * @throws EntityClassDoesNotExistException
     */
    private function getRepository(string $entityClass): EntityRepository
    {
        if (!\class_exists($entityClass)) {
            throw new EntityClassDoesNotExistException(
                \sprintf('Could not create repository from non-existant entity class "%s"', $entityClass)
            );
        }

        return $this->entityManager->getRepository($entityClass);
    }

    /**
     * @param class-string $entityClass
     *
     * @throws EntityClassDoesNotExistException
     */
    private function createQueryBuilder(string $alias, string $entityClass): QueryBuilder
    {
        return $this->getRepository($entityClass)->createQueryBuilder($alias);
    }
}
