<?php

namespace Framework\FileSystem;

use Framework\FileSystem\FileSystem;
use Framework\FileSystem\FileSystemInterface;

class ViewFileSystem extends FileSystem implements FileSystemInterface
{
    /**
     * Get the path to the view directory.
     *
     * @return string
     */
    public function getViewPath(): string
    {
        $viewPath = $this->directories['view'] ?? 'view';
        if (empty($viewPath)) {
            throw new \InvalidArgumentException("View path is not configured.");
        }
        return $this->getFullPath($viewPath);
    }

    /**
     * Get the path to a specific view file.
     *
     * @param string $viewFile
     * @return string
     */
    public function getViewFilePath(string $viewFile): string
    {
        return $this->getFullPath($this->getViewPath() . '/' . ltrim($viewFile, '/'));
    }
}