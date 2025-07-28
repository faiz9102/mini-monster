<?php

namespace Framework\FileSystem;

use Framework\ConfigProvider;
use Framework\FileSystem\Interfaces\ViewFileSystemInterface;

class ViewFileSystem extends BaseFileSystem implements ViewFileSystemInterface
{
    const string ADMIN_DIRECTORY = 'adminhtml';

    const string FRONTEND_DIRECTORY = 'frontend';

    const string BASE_DIRECTORY = 'base';

    const string LAYOUT_DIRECTORY = 'layout';

    const string TEMPLATES_DIRECTORY = 'templates';

    const string VIEW_DIRECTORY = 'view';

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

    public static function getLayoutPath(): string
    {
        return self::getViewPath() . DIRECTORY_SEPARATOR . self::LAYOUT_DIRECTORY;
    }

    public static function getTemplatesPath(): string
    {
        return self::getViewPath() . DIRECTORY_SEPARATOR . 'templates';
    }
}