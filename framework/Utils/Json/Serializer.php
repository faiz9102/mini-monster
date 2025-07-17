<?php

namespace framework\Utils\Json;

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
    public static function encode(array $data): string
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            throw new \RuntimeException("Failed to encode data to JSON: " . json_last_error_msg());
        }
        return $json;
    }

    /**
     * Decodes a JSON string into an associative array.
     *
     * @param string $json The JSON string to decode.
     * @return array The decoded associative array.
     */
    public static function decode(string $json): array
    {
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Invalid JSON: " . json_last_error_msg());
        }
        return $data;
    }
}