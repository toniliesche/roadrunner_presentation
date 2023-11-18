<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces;

use ToniLiesche\Roadrunner\Core\Domain\Login\Models\LoginPayload;
use ToniLiesche\Roadrunner\Core\Domain\Users\Models\User;

interface UserServiceInterface
{
    public function getUser(int $userId): User;

    public function validateLogin(LoginPayload $loginPayload);
}
