<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces;

use ToniLiesche\Roadrunner\Infrastructure\Log\Enums\LogCategory;

interface MessageProcessorInterface
{
    public function processMessage(string $message, array $context = [], LogCategory|string|null $logCategory = null): string;
}
