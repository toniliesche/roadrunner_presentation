<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Database\Users;

use Doctrine\DBAL\Exception;
use ToniLiesche\Roadrunner\Core\Application\Utility\Exceptions\ValidationFailedException;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserDataProviderInterface;
use ToniLiesche\Roadrunner\Core\Domain\Users\Models\User;
use ToniLiesche\Roadrunner\Infrastructure\Database\Shared\EntityClassDoesNotExistException;
use ToniLiesche\Roadrunner\Infrastructure\Shared\Exceptions\DataMappingException;
use ToniLiesche\Roadrunner\Infrastructure\Shared\Exceptions\DataProviderException;
use ToniLiesche\Roadrunner\Infrastructure\Shared\Exceptions\ItemNotFoundException;

readonly final class UserDataProvider implements UserDataProviderInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @throws DataMappingException
     * @throws DataProviderException
     * @throws ItemNotFoundException
     */
    public function getUser(int $userId): User
    {
        try {
            $userEntity = $this->userRepository->getUser($userId);
        } catch (Exception|EntityClassDoesNotExistException $ex) {
            throw new DataProviderException('Encountered error while fetching user from database.', $ex->getCode(), $ex);
        }
        if (null === $userEntity) {
            throw new ItemNotFoundException(\sprintf('Could not find user with id "%s"', $userId));
        }

        try {
            return UserMapper::databaseToModel($userEntity);
        } catch (ValidationFailedException $ex) {
            throw new DataMappingException('Encountered error while mapping user do business object.', $ex->getCode(), $ex);
        }
    }
}
