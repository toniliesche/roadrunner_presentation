<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Utility\Uuids\Interfaces;

interface UuidGeneratorInterface
{
    public function getUuid(): string;
}
