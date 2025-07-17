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
    public static function getRootPath(): string
    {
        $rootPath = ConfigProvider::getInstance()->get("directories")['root'];
        return realpath($rootPath);
    }

    public static function getFrameworkPath(): string
    {
        return self::getRootPath() . '/framework';
    }

    public static function getViewPath(): string
    {
        return realpath(self::getRootPath() . ConfigProvider::getInstance()->get("view", 'view'));
    }

    /**
     * Normalize a file path by removing redundant segments or unnecessary slashes and goBacks.
     */
    public static function normalizePath($path) : string
    {
        $parts = []; // Array to hold the path segments
        $path = str_replace('\\', '/', $path); // Normalize slashes
        $segments = explode('/', $path);

        foreach ($segments as $segment) {
            if ($segment === '' || $segment === '.') {
                continue;
            }
            if ($segment === '..') {
                array_pop($parts); // Go one directory up
            } else {
                $parts[] = $segment;
            }
        }

        $normalized = implode('/', $parts);
        // If path starts with "/", make sure it stays absolute
        if (str_starts_with($path, '/')) {
            $normalized = '/' . $normalized;
        }

        return $normalized;
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