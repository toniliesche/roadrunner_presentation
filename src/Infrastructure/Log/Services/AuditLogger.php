<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Services;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\AuditLoggerInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\ContextProcessorInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\MessageProcessorInterface;

readonly final class AuditLogger implements AuditLoggerInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private MessageProcessorInterface $messageProcessor,
        private ContextProcessorInterface $contextProcessor
    ) {
    }

    public function log(string $message, array $context = []): void
    {
        $context = $this->contextProcessor->processContext(LogLevel::INFO, $context);
        $this->logger->info(
            $this->messageProcessor->processMessage($message, $context),
            $context,
        );
    }
}
