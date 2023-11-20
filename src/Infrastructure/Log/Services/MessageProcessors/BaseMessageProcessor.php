<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Services\MessageProcessors;

use ToniLiesche\Roadrunner\Core\Application\Utility\Helpers\Services\StringHelper;
use ToniLiesche\Roadrunner\Infrastructure\Log\Enums\LogCategory;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\MessageProcessorInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\LogEntryContextProvider;

readonly final class BaseMessageProcessor implements MessageProcessorInterface
{
    public function __construct(private LogEntryContextProvider $logEntryContextProvider)
    {
    }

    public function processMessage(string $message, array $context = [], LogCategory|string|null $logCategory = null): string
    {
        if (isset($logCategory)) {
            if ($logCategory instanceof LogCategory) {
                $logCategory = $logCategory->value;
            }

            $message = \sprintf("[%s] %d/%s", \ucfirst($logCategory), $this->logEntryContextProvider->getPid(), $message);
        }

        if (!empty($context)) {
            $message = \sprintf("%s [%s]", $message, StringHelper::arrayToString($context));
        }

        return $message;
    }
}
