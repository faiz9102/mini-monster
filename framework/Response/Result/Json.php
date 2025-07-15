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
        parent::__construct($data, $statusCode, $headers, self::CONTENT_TYPE, $body);
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