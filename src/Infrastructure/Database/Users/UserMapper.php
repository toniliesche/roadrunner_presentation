<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Database\Users;

use ToniLiesche\Roadrunner\Core\Application\Utility\Exceptions\ValidationFailedException;
use ToniLiesche\Roadrunner\Core\Application\Utility\Traits\DataValidator;
use ToniLiesche\Roadrunner\Core\Domain\Users\Models\User;

class UserMapper
{
    use DataValidator;

    /**
     * @throws ValidationFailedException
     */
    public static function databaseToModel(UserEntity $userEntity): User
    {
        $data = [
            'id' => $userEntity->getId(),
            'name' => $userEntity->getName(),
            'username' => $userEntity->getUsername(),
            'password' => $userEntity->getPassword(),
        ];

        self::validateRules(User::getValidationRules(), $data);

        return new User($data);
    }
}
