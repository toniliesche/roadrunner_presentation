<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Services;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use ToniLiesche\Roadrunner\Infrastructure\Log\Enums\LogCategory;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\ApplicationLoggerInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\ContextProcessorInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\MessageProcessorInterface;

readonly final class ApplicationLogger implements ApplicationLoggerInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private MessageProcessorInterface $messageProcessor,
        private ContextProcessorInterface $contextProcessor,
    ) {
    }

    public function emergency(LogCategory|string $logCategory, string $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $logCategory, $message, $context);
    }

    public function alert(LogCategory|string $logCategory, string $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $logCategory, $message, $context);
    }

    public function critical(LogCategory|string $logCategory, string $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $logCategory, $message, $context);
    }

    public function error(LogCategory|string $logCategory, string $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $logCategory, $message, $context);
    }

    public function warning(LogCategory|string $logCategory, string $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $logCategory, $message, $context);
    }

    public function notice(LogCategory|string $logCategory, string $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $logCategory, $message, $context);
    }

    public function info(LogCategory|string $logCategory, string $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $logCategory, $message, $context);
    }

    public function debug(LogCategory|string $logCategory, string $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $logCategory, $message, $context);
    }

    private function log(string $logLevel, LogCategory|string $logCategory, string $message, array $context = []): void
    {
        $context = $this->contextProcessor->processContext($logLevel, $context, $logCategory);
        $this->logger->log(
            $logLevel,
            $this->messageProcessor->processMessage($message, $context, $logCategory),
            $context,
        );
    }
}
