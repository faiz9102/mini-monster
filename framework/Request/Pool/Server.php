<?php

namespace Framework\Request\Pool;

use Framework\Request\Interfaces\ServerInterface;

class Server implements ServerInterface
{
    private array $server;

    public function __construct()
    {
        $this->server = $_SERVER;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->server[$key] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->server;
    }

    /**
     * @inheritDoc
     */
    public function getRequestMethod(): string
    {
        return $this->get('REQUEST_METHOD', 'GET');
    }

    /**
     * @inheritDoc
     */
    public function getRequestUri(): string
    {
        return $this->get('REQUEST_URI', '/');
    }
}