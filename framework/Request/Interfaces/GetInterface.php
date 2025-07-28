<?php

namespace Framework\Request\Interfaces;

interface GetInterface
{
    /**
     * Get a value from the GET superglobal.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Get all values from the GET superglobal.
     *
     * @return array
     */
    public function getAll(): array;
}

