<?php

namespace Framework\FileSystem\Interfaces;

use Framework\FileSystem\Interfaces\BaseFileSystemInterface;

interface ViewFileSystemInterface extends BaseFileSystemInterface
{
    /**
     * Get the base view path.
     *
     * @return string
     */
    public static function getViewPath(): string;
}