<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces;

use ToniLiesche\Roadrunner\Core\Domain\Users\Models\User;

interface UserServiceInterface
{
    public function getUser(int $userId): User;
}
