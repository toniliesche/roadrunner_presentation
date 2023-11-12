<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Models;

use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\MissingConfigValueException;

readonly final class RouterConfig
{
    private bool $debugEnabled;

    private bool $cachingEnabled;

    private string $cachePath;

    /**
     * @throws MissingConfigValueException
     */
    public function __construct(array $data = [])
    {
        $this->debugEnabled = $data['debug'] ?? false;
        $this->cachingEnabled = $data['cache'] ?? false;

        if ($this->cachingEnabled) {
            $this->cachePath = $data['cachePath'] ?? throw new MissingConfigValueException(
                'Missing mandatory setting "cachePath" in router config section'
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

    public function getCachePath(): string
    {
        return $this->cachePath;
    }
}
