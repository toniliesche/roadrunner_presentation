<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Utility\Uuids\Services;

use Ramsey\Uuid\Uuid;
use ToniLiesche\Roadrunner\Core\Application\Utility\Uuids\Interfaces\UuidGeneratorInterface;

class UuidGenerator implements UuidGeneratorInterface
{
    public function getUuid(): string
    {
        return Uuid::uuid1()->toString();
    }
}
