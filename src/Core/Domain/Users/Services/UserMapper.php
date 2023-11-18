<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Users\Services;

use ToniLiesche\Roadrunner\Core\Domain\Users\Models\User;

class UserMapper
{
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
        ];
    }
}
