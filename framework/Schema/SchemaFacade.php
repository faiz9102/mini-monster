<?php

namespace Framework\Schema;

use Framework\Schema\Helper\Data;
use Framework\Schema\Helper\Data as SchemaHelper;
use Framework\Utils\Json\Serializer;

class SchemaFacade
{
    /**
     * @var string
     */
    const string SCHEMA_ID_PREFIX = 'schema:///';

    /**
     * @var Validator $validator
     */
    private Validator $validator;
    /**
     * @var SchemaHelper $helper
     */
    private SchemaHelper $helper;

    public function __construct(
        SchemaHelper $helper,
        Validator    $validator
    )
    {
        $this->helper = $helper;
        $this->validator = $validator;
    }

    public function getHelper(): SchemaHelper
    {
        return $this->helper;
    }

    public function getLoader()
    {
        return $this->validator->loader();
    }

    public function getValidator(): Validator
    {
        return $this->validator;
    }

    public function loadFrameworkSchema(): void
    {
        $schemas = Data::discoverSchemaFromFrameworkDir();

        foreach ($schemas as $name => $file) {
            try {
                $this->registerSchema($name, $file);
            } catch (\Exception $e) {
                error_log("Failed to register schema: {$name} | Error: " . $e->getMessage());
            }
        }
    }

    public function validate(string $filePath, string $schemaId): bool
    {
        $fileContent = file_get_contents($filePath);
        $fileContent = json_decode($fileContent, false);
        $result = $this->validator->validate($fileContent, self::SCHEMA_ID_PREFIX . $schemaId);

        return $result->isValid();
    }

    public function validateAndReturnContent(string $filePath, string $schemaId): array
    {
        $fileContent = file_get_contents($filePath);
        $fileContent = json_decode($fileContent, false);
        $result = $this->validator->validate($fileContent, self::SCHEMA_ID_PREFIX . $schemaId);

        if ($result->isValid()) {
            return (array)$fileContent;
        }

        throw new \Exception("Validation failed for schema ID: {$schemaId} | File: {$filePath}");
    }

    public function registerSchema(string $schemaName, string $schemaFile): void
    {
        if (!file_exists($schemaFile)) {
            throw new \Exception("Schema file does not exist: {$schemaFile}");
        }

        $jsonContent = file_get_contents($schemaFile);
        $object = Serializer::decode($jsonContent, false);

        $resolver = $this->validator->resolver();

        $isRegistered = $resolver->registerRaw($object, self::SCHEMA_ID_PREFIX . $schemaName);

        if ($isRegistered) {
            return;
        }

        throw new \Exception("Failed to register schema: ID: {$schemaName} | File: {$schemaFile}");
    }
}