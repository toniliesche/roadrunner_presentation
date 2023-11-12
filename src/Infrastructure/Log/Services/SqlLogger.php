<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Services;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\ContextProcessorInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\MessageProcessorInterface;

final readonly class SqlLogger implements LoggerInterface
{
    public function __construct(
        private LoggerInterface $sqlLogger,
        private MessageProcessorInterface $messageProcessor,
        private ContextProcessorInterface $contextProcessor
    ) {
    }

    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $context = $this->contextProcessor->processContext(LogLevel::INFO, $context);
        $this->sqlLogger->log(
            $level,
            $this->messageProcessor->processMessage($message, $context),
            $context,
        );
    }
}
