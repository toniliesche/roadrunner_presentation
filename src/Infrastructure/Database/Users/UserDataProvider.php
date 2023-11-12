<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Database\Users;

use ToniLiesche\Roadrunner\Core\Application\Utility\Models\Exceptions\ValidationFailedException;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserDataProviderInterface;
use ToniLiesche\Roadrunner\Core\Domain\Users\Models\User;
use ToniLiesche\Roadrunner\Infrastructure\Database\Shared\EntityClassDoesNotExistException;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\ItemNotFoundException;

readonly final class UserDataProvider implements UserDataProviderInterface
{
    public function __construct(private UserRepository $userRepository, private UserMapper $userMapper)
    {
    }

    /**
     * @throws EntityClassDoesNotExistException
     * @throws ItemNotFoundException
     * @throws ValidationFailedException
     */
    public function getUser(int $userId): User
    {
        $userEntity = $this->userRepository->getUser($userId);
        if (null === $userEntity) {
            throw new ItemNotFoundException(\sprintf('Could not find user with id "%s"', $userId));
        }

        return $this->userMapper->databaseToModel($userEntity);
    }
}
