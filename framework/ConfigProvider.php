<?php
namespace Framework;

class ConfigProvider
{
    const string CONFIG_FILE_PATH = BP . '/app/config/env.php';
    private static ?self $instance = null;
    private array $config;

    private function __construct()
    {
        $this->config = require self::CONFIG_FILE_PATH;
    }

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }
}