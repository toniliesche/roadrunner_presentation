<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces;

use ToniLiesche\Roadrunner\Infrastructure\Log\Enums\LogCategory;

interface ApplicationLoggerInterface
{

    public function emergency(LogCategory|string $logCategory, string $message, array $context = []): void;

    public function alert(LogCategory|string $logCategory, string $message, array $context = []): void;

    public function critical(LogCategory|string $logCategory, string $message, array $context = []): void;

    public function error(LogCategory|string $logCategory, string $message, array $context = []): void;

    public function warning(LogCategory|string $logCategory, string $message, array $context = []): void;

    public function notice(LogCategory|string $logCategory, string $message, array $context = []): void;

    public function info(LogCategory|string $logCategory, string $message, array $context = []): void;

    public function debug(LogCategory|string $logCategory, string $message, array $context = []): void;
}
