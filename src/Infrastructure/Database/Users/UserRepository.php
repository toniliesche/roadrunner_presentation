<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Database\Users;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use ToniLiesche\Roadrunner\Infrastructure\Database\Shared\EntityClassDoesNotExistException;
use ToniLiesche\Roadrunner\Infrastructure\Database\Traits\OrmAwareTrait;
use ToniLiesche\Roadrunner\Infrastructure\Log\Enums\LogCategory;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;

readonly final class UserRepository
{
    use OrmAwareTrait;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws EntityClassDoesNotExistException
     * @throws Exception
     */
    public function getUser(int $userId): ?UserEntity
    {
        Logging::application()?->debug(LogCategory::DATABASE, 'Requesting user from database by id.', ['userId' => $userId]);

        $repository = $this->getRepository(UserEntity::class);
//        $queryBuilder = $this->createQueryBuilder('u', UserEntity::class);
//
//        $query = $queryBuilder->select('u')
//            ->where($queryBuilder->expr()->eq('u.id', ':userId'))
//            ->setParameter('userId', $userId)
//            ->getQuery();

        try {
            $user = $repository->find($userId);
        } catch (NoResultException|NonUniqueResultException) {
            return null;
        }

        return $user;
    }
}
