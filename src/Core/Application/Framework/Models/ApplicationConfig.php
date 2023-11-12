<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Models;

readonly final class ApplicationConfig
{
    private string $name;

    private string $version;

    public function __construct(array $data = [])
    {
        $this->name = $data['name'] ?? 'unknown application';
        $this->version = $data['version'] ?? 'develop';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
