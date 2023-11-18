<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Models;

use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\MissingConfigValueException;

readonly final class DIConfig
{
    private bool $debugEnabled;

    private bool $cachingEnabled;

    private bool $compilationEnabled;

    private bool $proxyGenerationEnabled;

    private string $cachePath;

    /**
     * @throws MissingConfigValueException
     */
    public function __construct(array $data = [])
    {
        $this->debugEnabled = $data['debug'] ?? false;
        if (\PHP_SAPI === "cli" || false === \extension_loaded('apcu')) {
            $this->cachingEnabled = false;
        } else {
            $this->cachingEnabled = $data['cache'] ?? false;
        }
        $this->compilationEnabled = $data['compile'] ?? false;
        $this->proxyGenerationEnabled = $data['proxies'] ?? false;

        if ($this->cachingEnabled || $this->compilationEnabled || $this->proxyGenerationEnabled) {
            $this->cachePath = $data['cachePath'] ?? throw new MissingConfigValueException(
                'Missing mandatory setting "cachePath" in dependency injection config section'
            );
        }

    }

    public function isDebugEnabled(): bool
    {
        return $this->debugEnabled;
    }

    public function isCachingEnabled(): bool
    {
        return $this->cachingEnabled;
    }

    public function isCompilationEnabled(): bool
    {
        return $this->compilationEnabled;
    }

    public function isProxyGenerationEnabled(): bool
    {
        return $this->proxyGenerationEnabled;
    }

    public function getCachePath(): string
    {
        return $this->cachePath;
    }
}
