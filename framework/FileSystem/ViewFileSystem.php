<?php

namespace Framework\FileSystem;

use Framework\ConfigProvider;
use Framework\FileSystem\Interfaces\ViewFileSystemInterface;

class ViewFileSystem extends FileSystem implements ViewFileSystemInterface
{
    /**
     * Get the base view path
     *
     * @return string
     */
    public static function getViewPath(): string
    {
        $directory = ConfigProvider::getInstance()->get("view", 'view');
        $viewPath = self::getRootPath() . DIRECTORY_SEPARATOR . $directory;

        return trim($viewPath);
    }
}