<?php

namespace Framework\DI;

use Framework\DI\Interfaces\ContainerInterface;
use Framework\DI\Interfaces\ServiceProviderInterface;

class Container implements ContainerInterface
{
    protected static ?Container $instance = null;

    private static bool $isContainerBooted = false;
    protected array $bindings = [];
    protected array $instances = [];
    private array $serviceProviders = [];

    private function __construct()
    {
        // Prevents direct instantiation
    }

    private function __clone()
    {
        // Prevents cloning
    }


    /**
     * @inheritDoc
     */
    public static function getInstance(): ContainerInterface
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * @inheritDoc
     */
    public function get(string $type, array $args = []) : object
    {
        // Return the singleton instance if resolving the container itself
        if ($type === self::class || $type === static::class || $type === 'Framework\DI\Container') {
            return self::getInstance();
        }

        // Return existing instance if available
        if (isset($this->instances[$type])) {
            return $this->instances[$type];
        }

        // Use explicit binding if available
        if (isset($this->bindings[$type])) {
            $instance = call_user_func($this->bindings[$type]);
            $this->instances[$type] = $instance;
            return $instance;
        }

        // Auto-wire if class exists
        if (class_exists($type)) {
            try {
                $instance = $this->autowire($type, $args);
                $this->instances[$type] = $instance;
                return $instance;
            } catch (\Exception $e) {
                // Log the full exception details for debugging
                error_log("Container autowiring failed for {$type}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
                throw new \RuntimeException("Failed to autowire {$type}: " . $e->getMessage(), $e->getCode(), $e);
            }
        }

        throw new \RuntimeException("Cannot resolve {$type}");
    }

    /**
     * @inheritDoc
     */
    public function create(string $type, array $args = []): object
    {
        // Prevent creating the container itself
        if ($type === self::class || $type === static::class || $type === 'Framework\DI\Container') {
            return self::getInstance();
        }

        // Prevents ServiceProvider from being multiple times
        if (array_key_exists($type, $this->serviceProviders))
        {
            return $this->serviceProviders[$type];
        }

        // Use explicit binding if available
        if (isset($this->bindings[$type])) {
            return call_user_func($this->bindings[$type], $args);
        }
        // Auto-wire if class exists
        if (class_exists($type)) {
            try {
                return $this->autowire($type, $args);
            } catch (\Exception $e) {
                throw new \RuntimeException("Failed to create instance of {$type}: " . $e->getMessage());
            }
        }

        throw new \RuntimeException("Cannot create instance of {$type}: class does not exist.");
    }

    /**
     * Auto-wire a class and its dependencies
     */
    private function autowire(string $className, array $args = []): object
    {
        $reflector = new \ReflectionClass($className);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$className} is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            $instance = new $className();
            $this->injectDataIfSupported($instance, $args);
            return $instance;
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];
        $usedArgs = [];

        foreach ($parameters as $parameter) {
            $paramName = $parameter->getName();
            $type = $parameter->getType();

            // 1. Use explicitly passed $args (by name)
            if (array_key_exists($paramName, $args)) {
                $dependencies[] = $args[$paramName];
                $usedArgs[] = $paramName;
                continue;
            }

            // 2. Try resolving non-built-in types from container
            if ($type && !$type->isBuiltin()) {
                $dependencies[] = $this->get($type->getName());
                continue;
            }

            // 3. Fallback to default values
            if ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
                continue;
            }

            throw new \Exception("Cannot resolve parameter \${$paramName} for {$className}");
        }

        $instance = $reflector->newInstanceArgs($dependencies);

        // Filter out used args, pass remaining to _setData
        $remainingArgs = array_diff_key($args, array_flip($usedArgs));
        $this->injectDataIfSupported($instance, $remainingArgs);

        return $instance;
    }

    /**
     * Inject data into object if it supports DataInjectable trait
     */
    private function injectDataIfSupported(object $instance, array $args): void
    {
        if (method_exists($instance, '_setData')) {
            $instance->_setData($args);
        }
    }

    /**
     * @param string $type
     * @param callable $factory
     * @return void
     */
    public function bind(string $type, callable $factory): void
    {
        $this->bindings[$type] = $factory;
    }

    /**
     * Binds an interface to a concrete class.
     * So whenever the interface is requested,
     * the container will provide the concrete class.
     *
     * @param string $interface
     * @param string $concrete
     * @return void
     */
    public function bindInterface(string $interface, string $concrete): void
    {
        $this->bind($interface, function () use ($concrete) {
            return $this->get($concrete);
        });
    }

    public function bindSingleton(string $type, callable $factory): void
    {
        // Bind a singleton instance to the container
        $this->bindings[$type] = function () use ($type,$factory) {
            if (!isset($this->instances[$type])) {
                $this->instances[$type] = $factory();
            }
            return $this->instances[$type];
        };
    }

    /**
     * Registers a service provider class bindings
     * and Registers it with the container.
     *
     * @param string $class
     * @return void
     */
    public function registerProvider(string $class) : void
    {
        if (!class_exists($class)) {
            throw new \RuntimeException("Service provider class {$class} does not exist.");
        }

        if (!in_array(ServiceProviderInterface::class, class_implements($class))) {
            throw new \RuntimeException("Service provider {$class} must implement " . ServiceProviderInterface::class);
        }

        /** @var ServiceProviderInterface $provider */
        $provider = $this->get($class);

        $provider->register();
        $this->serviceProviders[$class] = $provider;
    }

    /**
     *
     *
     * @param string $directory
     * @param string $nameSpace
     * @return void
     */
    public function findAndLoadServiceProviders(string $directory, string $nameSpace): void
    {
        if (!is_dir($directory)) {
            throw new \RuntimeException("Directory {$directory} does not exist.");
        }

        $files = glob($directory . DIRECTORY_SEPARATOR . '*ServiceProvider.php',GLOB_NOSORT);

        foreach ($files as $index => $file) {
            $files[$index] = realpath($file);
        }

        $className = [];

        foreach ($files as $index => $filePath) {
            require_once $filePath;
            $className[$index] = "{$nameSpace}\\" . pathinfo($filePath, PATHINFO_FILENAME);
            $this->registerProvider($className[$index]);
        }


        foreach ($className as $class) {
            /**
             * @var \Framework\DI\AbstractServiceProvider $instance
             */
            $instance = $this->get($class);
            $this->serviceProviders[$class] = $instance;
        }

    }

    /**
     * Boots the container by calling the boot method
     * of each registered service provider.
     *
     * @return void
     */
    public function bootContainer(): void
    {
        if (self::$isContainerBooted) {
            return;
        }

        foreach ($this->serviceProviders as $serviceProvider) {
            $serviceProvider->boot();
        }
        self::$isContainerBooted = true;
    }
}