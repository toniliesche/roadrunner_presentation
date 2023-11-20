<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\FileSystem\Models;

use ToniLiesche\Roadrunner\Core\Application\Utility\Helpers\Services\StringHelper;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Interfaces\DirectoryInterface;

class Directory implements DirectoryInterface
{
    private string $path;

    private string $basePath;

    public function __construct(array $data = [])
    {
        if (isset($data['path'])) {
            $this->path = $data['path'];
        }

        if (isset($data['basePath'])) {
            $this->basePath = $data['basePath'];
        }
    }

    public function getPath(): string
    {
        return !empty($this->path) ? StringHelper::addTrailingSlash($this->path) : '';
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getBasePath(): string
    {
        return !empty($this->basePath) ? StringHelper::addTrailingSlash($this->basePath) : '';
    }

    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;
    }

    public function getAbsolutePath(): string
    {
        return StringHelper::addLeadingSlash($this->getBasePath() . $this->getPath());
    }

    public function checkPath(): bool
    {
        return \is_dir($this->getAbsolutePath());
    }

    public function createPath(int $mode = 0755, bool $recursive = true): bool
    {
        return \mkdir($this->getAbsolutePath(), $mode, $recursive);
    }
}
