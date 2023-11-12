<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Services\ContextProcessors;

use ToniLiesche\Roadrunner\Infrastructure\Log\Enums\LogCategory;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\ContextProcessorInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\LogEntryContextProvider;

readonly final class BaseContextProcessor implements ContextProcessorInterface
{
    public function __construct(private LogEntryContextProvider $contextProvider)
    {
    }

    public function processContext(string $logLevel, array $context = [], ?LogCategory $logCategory = null): array
    {
        $context = $this->contextProvider->populateContext($context, $logLevel, $logCategory);
        unset($context['logLevel']);

        return $context;
    }
}
