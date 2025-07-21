<?php

namespace Framework\Schema;

use Framework\Schema\Interfaces\ValidatorInterface;
use Opis\JsonSchema\CompliantValidator;

class Validator extends CompliantValidator implements ValidatorInterface
{
    /**
     * @inheritDoc
     */
    public function isValid(string $data,string $schemaId): bool
    {
        $result = parent::validate($data, $schemaId);
        return $result->isValid();
    }
}