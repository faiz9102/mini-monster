<?php

namespace Framework\Schema\Helper;

use Framework\FileSystem\BaseFileSystem;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;


class Data
{

    public static function getFrameworkDirectory(): string
    {
        return BaseFileSystem::getRootPath() . '/framework';
    }

    /**
     * Discover schema files in the framework directory.
     * And return an associative array where the key is the schema name
     * e.g. ["layout" => "framework/Layout/layout-schema.json"]
     *
     * @return array
     */
    public static function discoverSchemaFromFrameworkDir(): array
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