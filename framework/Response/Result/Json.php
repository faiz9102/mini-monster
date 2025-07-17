<?php

namespace Framework\Response\Result;

use Framework\Response\AbstractResponse;

class Json extends AbstractResponse
{
    public const CONTENT_TYPE = 'application/json';

    public function __construct(
        array $data = [],
        int $statusCode = 200,
        array $headers = []
    ) {
        parent::__construct('',$statusCode, $data, $headers, self::CONTENT_TYPE);
    }

    public function setData(sting $data): self
    {
        $this->setBody(json_encode($data, JSON_THROW_ON_ERROR));
        return $this;
    }

    public function validateBodyContent(string $content): bool
    {
        if (function_exists('json_validate')) {
            return json_validate($content);
        }
        json_decode($content);
        return (json_last_error() === JSON_ERROR_NONE);
    }
}