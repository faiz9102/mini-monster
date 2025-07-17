<?php

namespace Framework\Schema\Helper;

use Framework\FileSystem\FileSystem;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;


class Data
{

    public static function getFrameworkDirectory(): string
    {
        return FileSystem::getRootPath() . '/framework';
    }

    public static function diccoverSchemaFromFrameworkDir(): array
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(self::getFrameworkDirectory()));
        $schemaFiles = [];

        foreach ($iterator as $file) {
            if (
                $file->isFile() &&
                $file->getExtension() === 'json' &&
                str_ends_with($file->getBasename('.json'), '-schema')
            ) {
                $schemaName = mb_trim($file->getBasename('-schema.json'));
                $schemaFiles[$schemaName] = mb_trim($file->getPathname());
            }
        }

        return $schemaFiles;
    }
}