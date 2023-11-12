<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Models;

use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\MissingConfigValueException;

readonly final class DatabaseConfig
{
    private string $host;

    private int $port;

    private string $username;

    private string $password;

    private string $database;

    private string $cachePath;

    private string $proxyPath;

    /** @var array<int,string>  */
    private array $entityPaths;

    private bool $debugEnabled;

    private bool $cachingEnabled;

    /**
     * @throws MissingConfigValueException
     */
    public function __construct(array $data)
    {
        $this->host = $data['host'] ?? throw new MissingConfigValueException('Missing mandatory setting "host" in database config section');
        $this->username = $data['username'] ?? throw new MissingConfigValueException('Missing mandatory setting "username" in database config section');
        $this->password = $data['password'] ?? throw new MissingConfigValueException('Missing mandatory setting "password" in database config section');
        $this->database = $data['database'] ?? throw new MissingConfigValueException('Missing mandatory setting "database" in database config section');
        $this->cachePath = $data['cachePath'] ?? throw new MissingConfigValueException('Missing mandatory setting "cachePath" in database config section');
        $this->proxyPath = $data['proxyPath'] ?? throw new MissingConfigValueException('Missing mandatory setting "proxyPath" in database config section');
        $this->entityPaths = $data['entityPaths'] ?? [];

        $this->port = $data['port'] ?? 3306;
        $this->debugEnabled = $data['debug'] ?? false;
        $this->cachingEnabled = $data['cache'] ?? true;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getCachePath(): string
    {
        return $this->cachePath;
    }

    public function getProxyPath(): string
    {
        return $this->proxyPath;
    }

    public function getEntityPaths(): array
    {
        return $this->entityPaths;
    }

    public function isDebugEnabled(): bool
    {
        return $this->debugEnabled;
    }

    public function isCachingEnabled(): bool
    {
        return $this->cachingEnabled;
    }
}
