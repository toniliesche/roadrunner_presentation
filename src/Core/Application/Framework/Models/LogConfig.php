<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Models;

use ToniLiesche\Roadrunner\Core\Application\Framework\Enums\LogType;

readonly final class LogConfig
{
    private bool $debugEnabled;

    private string $logPath;

    private string $logLevel;

    private LogType $logType;

    public function __construct(array $data = [])
    {
        $this->debugEnabled = $data['debug'] ?? false;
        $this->logPath = $data['logPath'] ?? \sprintf("%s/log/", \APP_BASE_PATH);
        $this->logLevel = $data['logLevel'] ?? 'debug';
        $this->logType = isset($data['logType']) ? LogType::fromString($data['logType']) : LogType::LOCAL;
    }

    public function isDebugEnabled(): bool
    {
        return $this->debugEnabled;
    }

    public function getLogPath(): string
    {
        return $this->logPath;
    }

    public function getLogLevel(): string
    {
        return $this->logLevel;
    }

    public function getLogType(): LogType
    {
        return $this->logType;
    }
}
