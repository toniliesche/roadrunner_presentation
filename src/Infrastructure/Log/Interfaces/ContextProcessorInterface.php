<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces;

use ToniLiesche\Roadrunner\Infrastructure\Log\Enums\LogCategory;

interface ContextProcessorInterface
{
    public function processContext(string $logLevel, array $context = [], ?LogCategory $logCategory = null): array;
}
