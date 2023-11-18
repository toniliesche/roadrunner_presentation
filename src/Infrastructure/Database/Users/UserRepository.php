<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Database\Users;

use Doctrine\ORM\EntityManagerInterface;
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
     */
    public function getUser(int $userId): ?UserEntity
    {
        Logging::application()?->debug(
            LogCategory::DATABASE,
            'Requesting user from database by id.',
            ['userId' => $userId]
        );

        return $this->getRepository(UserEntity::class)->find($userId);
    }

    /**
     * @throws EntityClassDoesNotExistException
     */
    public function getUserByUsername(string $username): ?UserEntity
    {
        Logging::application()?->debug(
            LogCategory::DATABASE,
            'Requesting user from database by username.',
            ['username' => $username]
        );

        return $this->getRepository(UserEntity::class)->findOneBy(['username' => $username]);
    }
}
