<?php

namespace Framework\Request\Interfaces;

interface ServerInterface
{
    /**
     * Get a value from the SERVER superglobal.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Get all values from the SERVER superglobal.
     *
     * @return array
     */
    public function getAll(): array;

    /**
     * Get the request method.
     *
     * @return string
     */
    public function getRequestMethod(): string;

    /**
     * Get the request URI.
     *
     * @return string
     */
    public function getRequestUri(): string;
}

