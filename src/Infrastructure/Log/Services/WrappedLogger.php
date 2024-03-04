<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Services;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use ToniLiesche\Roadrunner\Infrastructure\Log\Enums\LogCategory;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\ApplicationLoggerInterface;

readonly final class WrappedLogger implements LoggerInterface
{
    public function __construct(private ApplicationLoggerInterface $logger)
    {
    }

    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->logger->emergency(LogCategory::SYSTEM, $message, $context);
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->logger->alert(LogCategory::SYSTEM, $message, $context);
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->logger->critical(LogCategory::SYSTEM, $message, $context);
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->logger->error(LogCategory::SYSTEM, $message, $context);
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->logger->warning(LogCategory::SYSTEM, $message, $context);
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->logger->notice(LogCategory::SYSTEM, $message, $context);
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->logger->info(LogCategory::SYSTEM, $message, $context);
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->logger->debug(LogCategory::SYSTEM, $message, $context);
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        match($level) {
            LogLevel::EMERGENCY => $this->emergency($message, $context),
            LogLevel::ALERT => $this->alert($message, $context),
            LogLevel::CRITICAL => $this->critical($message, $context),
            LogLevel::ERROR => $this->error($message, $context),
            LogLevel::WARNING => $this->warning($message, $context),
            LogLevel::NOTICE => $this->notice($message, $context),
            LogLevel::INFO => $this->info($message, $context),
            LogLevel::DEBUG => $this->debug($message, $context),
        };
    }
}
