<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Database\Users;

use ToniLiesche\Roadrunner\Core\Application\Utility\Exceptions\ValidationFailedException;
use ToniLiesche\Roadrunner\Core\Domain\Users\Models\User;

class UserMapper
{
    /**
     * @throws ValidationFailedException
     */
    public static function databaseToModel(UserEntity $userEntity): User
    {
        return new User(
            [
                'id' => $userEntity->getId(),
                'name' => $userEntity->getName(),
                'username' => $userEntity->getUsername(),
            ]
        );
    }
}
