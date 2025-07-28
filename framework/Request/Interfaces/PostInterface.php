<?php

namespace Framework\Request\Interfaces;

interface PostInterface
{
    /**
     * Get a value from the POST superglobal.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Get all values from the POST superglobal.
     *
     * @return array
     */
    public function getAll(): array;
}

