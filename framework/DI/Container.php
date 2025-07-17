<?php

namespace Framework\DI;

class Container
{
    private static ?Container $instance = null;
    public array $bindings = [];
    private array $instances = [];

    private function __construct() {} // Prevent direct instantiation

    public static function getInstance(): Container
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function bind(string $abstract, callable $factory): void
    {
        $this->bindings[$abstract] = $factory;
    }

    public function resolve(string $abstract)
    {
        // Return the singleton instance if resolving the container itself
        if ($abstract === self::class || $abstract === static::class || $abstract === 'Framework\DI\Container') {
            return self::getInstance();
        }

        // Return existing instance if available
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        // Use explicit binding if available
        if (isset($this->bindings[$abstract])) {
            $instance = call_user_func($this->bindings[$abstract]);
            $this->instances[$abstract] = $instance;
            return $instance;
        }

        // Special case for 'config' array
        if ($abstract === 'config') {
            return $this->bindings['config'] ? call_user_func($this->bindings['config']) : [];
        }

        // Auto-wire if class exists
        if (class_exists($abstract)) {
            try {
                $instance = $this->autowire($abstract);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
            $this->instances[$abstract] = $instance;
            return $instance;
        }

        throw new \Exception("Cannot resolve {$abstract}");
    }

    private function autowire(string $className)
    {
        // Prevent autowiring the container itself
        if ($className === self::class || $className === static::class || $className === 'Framework\DI\Container') {
            return self::getInstance();
        }

        $reflector = new \ReflectionClass($className);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$className} is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        // If no constructor, just return new instance
        if ($constructor === null) {
            return new $className();
        }

        // Get constructor parameters
        $parameters = $constructor->getParameters();

        if (count($parameters) === 0) {
            return new $className();
        }

        // Resolve each parameter
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            // Handle array $config = [] injection
            if ($parameter->getName() === 'config' && $type && $type->getName() === 'array') {
                $dependencies[] = $this->resolve('config');
            } elseif ($type === null || $type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Cannot resolve parameter {$parameter->getName()} in {$className}");
                }
            } else {
                $dependencies[] = $this->resolve($type->getName());
            }
        }

        // Create new instance with dependencies
        return $reflector->newInstanceArgs($dependencies);
    }

    // Add this method to your Container class
    public function bindInterface(string $interface, string $concrete): void
    {
        $this->bind($interface, function () use ($concrete) {
            return $this->resolve($concrete);
        });
    }

    // Add to Container class
    public function registerProvider(string $providerClass): void
    {
        $provider = new $providerClass($this);
        $provider->register();
    }

    // Add to Container class
    public function singleton(string $abstract, callable $factory): void
    {
        $this->bind($abstract, function () use ($abstract, $factory) {
            $instance = call_user_func($factory);
            $this->instances[$abstract] = $instance;
            return $instance;
        });
    }

    // Add to Container class
    public function scanAndRegister(string $directory, string $namespace): void
    {
        $files = glob($directory . '/*.php');
        foreach ($files as $file) {
            $className = $namespace . '\\' . pathinfo($file, PATHINFO_FILENAME);
            if (class_exists($className)) {
                // Register class in container
                $this->bind($className, function () use ($className) {
                    return $this->autowire($className);
                });
            }
        }
    }
}