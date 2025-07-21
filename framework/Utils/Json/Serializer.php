<?php

namespace Framework\Utils\Json;

/**
 * JsonHelper class provides utility functions for working with JSON data.
 */
class Serializer
{
    /**
     * Converts an associative array to a JSON string.
     *
     * @param array $data The associative array to convert.
     * @return string The JSON string representation of the array.
     */
    public static function encode(array $data, int $flags = JSON_UNESCAPED_SLASHES, int $depth = 512): string
    {
        $json = json_encode($data, $flags, $depth);
        if ($json === false) {
            throw new \RuntimeException("Failed to encode data to JSON: " . json_last_error_msg());
        }
        return $json;
    }

    /**
     * Decodes a JSON string into an associative array.
     *
     * @param string $json The JSON string to decode.
     * @return mixed The decoded associative array.
     */
    public static function decode(string $json, bool $associative = true, int $depth = 512, int $flags = 0): mixed
    {
        $data = json_decode($json, $associative, $depth, $flags);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Invalid JSON: " . json_last_error_msg());
        }
        return $data;
    }
}