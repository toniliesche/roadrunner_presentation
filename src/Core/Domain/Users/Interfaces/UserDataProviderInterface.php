<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces;

use ToniLiesche\Roadrunner\Core\Domain\Users\Models\User;
use ToniLiesche\Roadrunner\Infrastructure\Shared\Exceptions\DataMappingException;
use ToniLiesche\Roadrunner\Infrastructure\Shared\Exceptions\DataProviderException;
use ToniLiesche\Roadrunner\Infrastructure\Shared\Exceptions\ItemNotFoundException;

interface UserDataProviderInterface
{
    /**
     * @throws DataMappingException
     * @throws DataProviderException
     * @throws ItemNotFoundException
     */
    public function getUser(int $userId): User;
}
