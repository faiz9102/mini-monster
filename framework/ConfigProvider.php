<?php
namespace App;

class ConfigProvider
{
    private static ?self $instance = null;
    private array $config;

    private function __construct()
    {
        $this->config = require __DIR__ . '/../config/env.php';
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
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return $this->config[$key] ?? $default;
    }

    public function set(string $key, mixed $value): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        if(!array_key_exists($key, $this->config)) {
            $this->config[$key] = $value;
            return $this;
        }
        throw new \InvalidArgumentException("Key '$key' already exists in the configuration.");
    }


    // TODO: remove this getViewDir method in the future and  use it's implementation from FileSystem class
    public static function getViewDir(): string
    {
        return self::getInstance()->get('view', 'view');
    }
}