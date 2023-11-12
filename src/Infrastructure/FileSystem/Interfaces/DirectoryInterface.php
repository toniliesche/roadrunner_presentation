<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\FileSystem\Interfaces;

interface DirectoryInterface
{
    public function getPath(): string;

    public function setPath(string $path): void;

    public function getBasePath(): string;

    public function setBasePath(string $basePath): void;

    public function getAbsolutePath(): string;
}
