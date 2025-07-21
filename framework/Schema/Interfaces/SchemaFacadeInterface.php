<?php

namespace Framework\Schema\Interfaces;

use Exception;

interface SchemaFacadeInterface
{
    /**
     * Scans the framework directory and loads found schema into the class.
     * This method is typically called during the application bootstrap process
     *
     * Schema file naming convention is: `<schemaName>-schema.json`
     *
     * @return void
     */
    public function loadFrameworkSchema(): void;

    /**
     * Registers a schema by GivenName and file path.
     *
     * @param string $schemaName
     * @param string $schemaFile
     * @return void
     */
    public function registerSchema(string $schemaName, string $schemaFile): void;

    /**
     * Validates a file against a schema by its Name.
     *
     * @param string $filePath
     * @param string $schemaName
     * @return bool
     */
    public function validate(string $filePath, string $schemaName): bool;

    /**
     * Validates a file against a schema by its Name and returns the content of the file.
     *
     * @param string $filePath
     * @param string $schemaName
     * @return array
     * @throws Exception
     */
    public function validateAndReturnContent(string $filePath, string $schemaName): array;
}