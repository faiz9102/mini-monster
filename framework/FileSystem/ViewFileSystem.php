<?php

namespace Framework\FileSystem;

use Framework\FileSystem\FileSystem;
use Framework\FileSystem\FileSystemInterface;
use App\ConfigProvider;

class ViewFileSystem extends FileSystem implements FileSystemInterface
{
    /**
     * Get the view path with a trailing directory separator.
     *
     * @param string $viewFile
     * @return string
     */
    public static function getViewPath(): string
    {
        $directory = ConfigProvider::getViewDir();
        $viewPath = self::getRootPath() . '/' . $directory;

        return trim($viewPath);
    }
}