<?php

namespace Framework\Request\Pool;

use Framework\Request\Interfaces\PostInterface;

class Post implements PostInterface
{
    private array $post;

    public function __construct()
    {
        $this->post = $_POST;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    public function getAll(): array
    {
        return $this->post;
    }
}

