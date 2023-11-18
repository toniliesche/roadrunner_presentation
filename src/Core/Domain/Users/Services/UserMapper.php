<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Users\Services;

use ToniLiesche\Roadrunner\Core\Application\Utility\Traits\DataValidator;
use ToniLiesche\Roadrunner\Core\Domain\Users\Models\User;

class UserMapper
{
    use DataValidator;

    /**
     * @return array{
     *             id: int,
     *             name: string,
     *             username: string
     *         }
     */
    public static function modelToArray(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
        ];
    }
}
