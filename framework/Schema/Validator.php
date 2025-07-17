<?php

namespace Framework\Schema;

use Framework\Schema\Interfaces\ValidatorInterface;
use Opis\JsonSchema\CompliantValidator;

class Validator extends CompliantValidator implements ValidatorInterface
{
    /**
     * Validates the given data against the provided schema.
     *
     * @param mixed $data The data to validate.
     * @param string $schema The schema to validate against.
     * @return bool True if validation passes, false otherwise.
     */
    public function isValid(mixed $data,string $schema): bool
    {
        $result = parent::validate($data, $schema);
        return $result->isValid();
    }
}