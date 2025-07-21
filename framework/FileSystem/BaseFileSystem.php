<?php

namespace Framework\FileSystem;

use Framework\ConfigProvider;
use Framework\FileSystem\Interfaces\BaseFileSystemInterface;

class BaseFileSystem implements BaseFileSystemInterface
{
    protected array $directories = [];

    public function __construct()
    {
        $this->directories = ConfigProvider::getInstance()->get("directories");
    }

    /**
     * Get the root path of the file system.
     *
     * @return string
     */
    public static function getRootPath(): string
    {
        $rootPath = BP ?? realpath(__DIR__ . "/../..");
        return realpath(rtrim($rootPath, DIRECTORY_SEPARATOR));
    }

    public static function getFrameworkDirectoryPath(): string
    {
        return self::getRootPath() . '/framework';
    }

    /**
     * Normalize a file path by removing redundant segments or unnecessary slashes and goBacks.
     */
    public static function normalizePath($path) : string
    {
        return realpath($path);
    }
}