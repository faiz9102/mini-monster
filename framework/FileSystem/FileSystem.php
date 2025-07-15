<?php

namespace Framework\FileSystem;

use Framework\FileSystem\FileSystemInterface;
use App\ConfigProvider;
class FileSystem implements FileSystemInterface
{
    protected array $directories = [];

    public function __construct(ConfigProvider $configProvider)
    {
        $this->directories = $configProvider::getInstance()->get("directories");
    }

    /**
     * Get the root path of the file system.
     *
     * @return string
     */
    public function getRootPath(): string
    {
        $path = rtrim($this->directories['root'], '/') . '/';
        if (!is_dir($path)) {
            throw new \Exception("Root path does not exist: " . $path);
        }
        return $this->stripGoBacks($path);
    }

    /**
     * Strip go backs '../' from a given path and balances the path by removing the effect of '../'.
     */
    public function stripGoBacks(string $path): string
    {
        $parts = explode('/', $path);
        $balancedParts = [];

        foreach ($parts as $part) {
            if ($part === '' || $part === '.') {
                continue; // Skip empty parts and current directory references
            }
            if ($part === '..') {
                array_pop($balancedParts); // Go back one directory
            } else {
                $balancedParts[] = $part; // Add the current part
            }
        }

        return implode('/', $balancedParts);
    }

    /**
     * Get the full path for a given relative path.
     *
     * @param string $path
     * @return string
     */
    public function getFullPath(string $path): string
    {
        return rtrim($this->getRootPath(), '/') . '/' . ltrim($path, '/');
    }
}