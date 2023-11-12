<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces;

use ToniLiesche\Roadrunner\Core\Domain\Users\Models\User;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\ItemNotFoundException;

interface UserDataProviderInterface
{
    /**
     * @throws ItemNotFoundException
     */
    public function getUser(int $userId): User;
}
