<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Models;

use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\InvalidConfigValueException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\MissingConfigValueException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\Helpers\ArrayHelper;

readonly final class TemplatingConfig
{
    private string $templatePath;

    /** @var array<string,string> */
    private array $baseVars;

    private string $cachePath;

    private bool $cachingEnabled;

    /**
     * @throws InvalidConfigValueException
     * @throws MissingConfigValueException
     */
    public function __construct(array $data = [])
    {
        $this->templatePath = $data['templatePath'] ?? throw new MissingConfigValueException(
            'Missing mandatory setting "templatePath" in templating config section'
        );

        $this->cachingEnabled = $data['cache'] ?? false;
        if ($this->cachingEnabled) {
            $this->cachePath = $data['cachePath'] ?? throw new MissingConfigValueException(
                'Missing mandatory setting "cachePath" in templating config section'
            );
        }

        $this->baseVars = $data['baseVars'] ?? [];
        if (false === ArrayHelper::isStringHashmap($this->baseVars)) {
            throw new InvalidConfigValueException('Setting "baseVars" inside templating config section may only contain strings as keys AND values.');
        }
    }

    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    public function getCachePath(): string
    {
        return $this->cachePath;
    }

    public function isCachingEnabled(): bool
    {
        return $this->cachingEnabled;
    }

    /**
     * @return array<string,string>
     */
    public function getBaseVars(): array
    {
        return $this->baseVars;
    }
}
