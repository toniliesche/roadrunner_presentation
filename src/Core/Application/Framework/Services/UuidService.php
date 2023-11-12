<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Services;

use Ramsey\Uuid\Uuid;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\UuidServiceInterface;

class UuidService implements UuidServiceInterface
{
    public function getUuid(): string
    {
        return Uuid::uuid1()->toString();
    }
}
