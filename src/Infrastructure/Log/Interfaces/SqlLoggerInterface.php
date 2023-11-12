<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces;

interface SqlLoggerInterface
{
    public function log(string $message): void;
}
