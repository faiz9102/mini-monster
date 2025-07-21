<?php

namespace Framework\Schema\Interfaces;
interface ValidatorInterface
{
    /**
     * Validates the given data against the provided schema.
     *
     * @param mixed $data The data to validate.
     * @param string $schemaId The schema to validate against.
     * @return bool True if validation passes, false otherwise.
     */
    public function isValid(string $data,string $schemaId): bool;
}