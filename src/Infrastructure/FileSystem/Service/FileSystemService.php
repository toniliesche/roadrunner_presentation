<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\FileSystem\Service;

use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Exceptions\FileSystemException;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Interfaces\DirectoryInterface;

class FileSystemService
{
    private array $inodeInformation;

    private int $lastInodeUpdate;

    public function checkDirectoryExists(
        DirectoryInterface $directory,
    ): bool {
        return \is_dir($directory->getAbsolutePath());
    }

    public function createDirectory(
        DirectoryInterface $directory,
        int $mode = 0755,
        bool $recursive = false): bool
    {
        return \mkdir($directory->getAbsolutePath(), $mode, $recursive);
    }

    public function checkDirectoryIsReadable(DirectoryInterface $directory): bool
    {
        return $this->checkPathIsReadable($directory->getAbsolutePath());
    }

    public function checkFileIsWritable(DirectoryInterface $directory, string $file): bool
    {
        $path = \sprintf("%s/%s", $directory->getAbsolutePath(), $file);

        return $this->checkPathIsWritable($path, false);
    }

    public function checkDirectoryIsWritable(DirectoryInterface $directory): bool
    {
        return $this->checkPathIsWritable($directory->getAbsolutePath());
    }

    public function checkPathIsReadable(string $path): bool
    {
        return \is_readable($path);
    }

    public function checkPathIsWritable(string $path, bool $isDir = true): bool
    {
        // if new file is created, we need to check for write permissions on underlying directory
        if (false === $isDir && !\file_exists($path)) {
            return \is_writable(\dirname($path));
        }

        return \is_writable($path);
    }

    /**
     * @throws FileSystemException
     */
    public function checkUsedSpaceBelowThreshold(string $path, float $threshold = 0.8): bool
    {
        $freeSpace = \disk_free_space($path);
        $totalSpace = \disk_total_space($path);

        if (false === $freeSpace) {
            throw new FileSystemException(\sprintf('Could not read disk_free_space or disk_total_space on %s', $path));
        }

        return (1 - $threshold) > ($freeSpace / $totalSpace);
    }

    /**
     * @throws FileSystemException
     */
    public function checkInodeTableUsageBelowThreshold(string $path, float $threshold = 0.8): bool
    {
        $inodeInfo = $this->parseInodeTableInformation($path);

        return (1 - $threshold) > ((int)$inodeInfo[3] / (int)$inodeInfo[1]);
    }

    /**
     * @throws FileSystemException
     */
    private function parseInodeTableInformation(string $path): array
    {
        if (!isset($this->inodeInformation) || $this->lastInodeUpdate < (\time() - 5)) {
            $inodeInformation = \explode("\n", \shell_exec('df -i'));
            \array_shift($inodeInformation);

            $this->inodeInformation = $inodeInformation;
            $this->lastInodeUpdate = \time();
        }

        $length = 0;
        $info = [];
        foreach ($this->inodeInformation as $row) {
            $rowParts = \preg_split('/\s+/', $row);
            if (\count($rowParts) < 6 || 'Filesystem' === $rowParts[0]) {
                continue;
            }

            if (\strlen($rowParts[5]) <= $length) {
                continue;
            }

            if (!\str_starts_with($path, $rowParts[5])) {
                continue;
            }

            $length = \strlen($rowParts[5]);
            $info = $rowParts;
        }

        if (empty($info)) {
            throw new FileSystemException(\sprintf('Could not read inode table information on %s', $path));
        }

        return $info;
    }
}
