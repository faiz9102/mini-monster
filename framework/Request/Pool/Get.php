<?php

namespace Framework\Request\Pool;

use Framework\Request\Interfaces\GetInterface;

class Get implements GetInterface
{
    private array $get;

    public function __construct()
    {
        $this->get = $_GET;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    public function getAll(): array
    {
        return $this->get;
    }
}

