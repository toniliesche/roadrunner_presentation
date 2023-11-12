<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Services;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\AuditLoggerInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\ContextProcessorInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\MessageProcessorInterface;

final readonly class AuditLogger implements AuditLoggerInterface
{
    public function __construct(
        private LoggerInterface $auditLogger,
        private MessageProcessorInterface $messageProcessor,
        private ContextProcessorInterface $contextProcessor
    ) {
    }

    public function log(string $message, array $context = []): void
    {
        $context = $this->contextProcessor->processContext(LogLevel::INFO, $context);
        $this->auditLogger->info(
            $this->messageProcessor->processMessage($message, $context),
            $context,
        );
    }
}
