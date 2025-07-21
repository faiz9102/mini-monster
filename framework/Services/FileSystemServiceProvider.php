<?php

namespace App\Services;

use Framework\ConfigProvider;
use Framework\DI\AbstractServiceProvider;
use Framework\FileSystem\BaseFileSystem;
use Framework\FileSystem\Interfaces\BaseFileSystemInterface;

class FileSystemServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        // Register FileSystem implementation
        $this->container->bindInterface(BaseFileSystemInterface::class, BaseFileSystem::class);

        // Register FileSystem as singleton
        $this->container->bind(BaseFileSystem::class, function () {
            return new BaseFileSystem();
        });
    }
}
