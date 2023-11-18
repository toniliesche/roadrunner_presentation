<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Users\Services;

use ToniLiesche\Roadrunner\Core\Domain\Shared\Enums\ErrorCode;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserDataProviderInterface;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Core\Domain\Users\Models\User;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\InternalServerErrorException;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\NotFoundException;
use ToniLiesche\Roadrunner\Infrastructure\Shared\Exceptions\DataMappingException;
use ToniLiesche\Roadrunner\Infrastructure\Shared\Exceptions\DataProviderException;
use ToniLiesche\Roadrunner\Infrastructure\Shared\Exceptions\ItemNotFoundException;

readonly final class UserService implements UserServiceInterface
{
    public function __construct(private UserDataProviderInterface $userDataProvider)
    {
    }

    /**
     * @throws InternalServerErrorException
     * @throws NotFoundException
     */
    public function getUser(int $userId): User
    {
        try {
            return $this->userDataProvider->getUser($userId);
        } catch (DataMappingException|DataProviderException $ex) {
            throw new InternalServerErrorException('Could not retrieve user from data source.', $ex);
        } catch (ItemNotFoundException $ex) {
            throw new NotFoundException('Could not find requested user.', $ex, ErrorCode::E_USER_NOT_FOUND);
        }
    }
}
