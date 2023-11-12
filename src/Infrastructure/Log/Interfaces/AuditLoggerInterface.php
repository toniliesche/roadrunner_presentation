<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces;

interface AuditLoggerInterface
{
    public function log(string $message, array $context = []): void;
}
