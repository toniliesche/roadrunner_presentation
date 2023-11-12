<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Ping\Interfaces;

interface PingServiceInterface
{
    public function ping(string $host): void;
}
