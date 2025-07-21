<?php

namespace App\Services;

use App\ConfigProvider;
use Framework\DI\AbstractServiceProvider;
use Framework\FileSystem\FileSystem;
use Framework\FileSystem\FileSystemInterface;

class FileSystemServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        // Register FileSystem implementation
        $this->container->bindInterface(FileSystemInterface::class, FileSystem::class);

        // Register FileSystem as singleton
        $this->container->bind(FileSystem::class, function () {
            return new FileSystem(ConfigProvider::getInstance());
        });
    }
}
