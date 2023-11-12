<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Users\Services;

use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserDataProviderInterface;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Core\Domain\Users\Models\User;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\ItemNotFoundException;

readonly final class UserService implements UserServiceInterface
{
    public function __construct(private UserDataProviderInterface $userDataProvider)
    {
    }

    /**
     * @throws ItemNotFoundException
     */
    public function getUser(int $userId): User
    {
        return $this->userDataProvider->getUser($userId);
    }
}
