<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Wrappers;

use Psr\Log\LoggerInterface;
use RoadRunner\Logger\Logger;

class RoadRunnerLogger implements LoggerInterface
{
    public function __construct(private readonly Logger $logger)
    {
    }

    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->logger->log($message, $context);
    }
}
