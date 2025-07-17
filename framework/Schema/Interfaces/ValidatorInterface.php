<?php

namespace Framework\Schema\Interfaces;
interface ValidatorInterface
{
    /**
     * Validate the given data against the schema.
     *
     * @param mixed $data The data to validate.
     * @param string $schema The schema to validate against.
     * @return bool True if valid, false otherwise.
     */
    public function isValid(mixed $data,string $schemaName): bool;
}