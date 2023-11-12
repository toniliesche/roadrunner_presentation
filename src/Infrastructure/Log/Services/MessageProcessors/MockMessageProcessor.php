<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Services\MessageProcessors;

use ToniLiesche\Roadrunner\Infrastructure\Log\Enums\LogCategory;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\MessageProcessorInterface;

class MockMessageProcessor implements MessageProcessorInterface
{
    public function processMessage(string $message, array $context = [], LogCategory|string|null $logCategory = null): string
    {
        return $message;
    }
}
