<?php

namespace Framework\Schema;

use Framework\Schema\Interfaces\ValidatorInterface;
use Opis\JsonSchema\SchemaLoader;
use opis\JsonSchema\Validator as OpisValidator;

abstract class OldValidator extends OpisValidator implements ValidatorInterface
{
    public function __construct(
        ?SchemaLoader $loader = null,
        int $max_errors = 1,
        bool $stop_at_first_error = true
    ) {
        parent::__construct($loader, $max_errors, $stop_at_first_error);
    }

    /**
     * Validate the given data against the schema.
     *
     * @param mixed $data The data to validate.
     * @param string $schema The schema to validate against.
     * @return bool True if valid, false otherwise.
     */
    public function isValid(mixed $data, string $schemaName): bool
    {
        $result = $this->validate($data, $schemaName);
        if ($result->isValid()) {
            return true;
        } else {
            // Handle validation errors
            foreach ($result->getErrors() as $error) {
                // Log or handle the error as needed
                // For example, you could throw an exception or log it
                error_log($error->getMessage());
            }
            return false;
        }
    }

}