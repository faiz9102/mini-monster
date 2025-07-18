<?php

namespace Framework\DI;

class Container
{
    protected static ?Container $instance = null;
    protected array $bindings = [];
    protected array $instances = [];

    private function __construct()
    {
        // Prevents direct instantiation
    }

    private function __clone()
    {
        // Prevents cloning
    }


    /**
     * Returns the singleton instance of the Container.
     *
     * @return Container
     */
    public static function getInstance(): Container
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param string $type
     * @param array $args
     * @return object
     * @throws \RuntimeException
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
     * Creates a new instance of the specified type.
     * This method is useful for ensuring that the instance created is not cached
     *
     * @param string $type
     * @param array $args
     * @return object
     * @throws \RuntimeException
     */
    public function create(string $type, array $args = []): object
    {
        // Prevent creating the container itself
        if ($type === self::class || $type === static::class || $type === 'Framework\DI\Container') {
            return self::getInstance();
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
    private function autowire(string $className, array $args = [])
    {
        $reflector = new \ReflectionClass($className);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$className} is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            // If no constructor and we have args, check if class has a $_data property or method to handle it
            $instance = new $className();
            $this->injectDataIfSupported($instance, $args);
            return $instance;
        }

        /**
         * @var \ReflectionParameter[] $parameters
         */
        $parameters = $constructor->getParameters();

        if (count($parameters) === 0) {
            // If constructor has no parameters, but we have args, try to set $_data property
            $instance = new $className();
            $this->injectDataIfSupported($instance, $args);
            return $instance;
        }

        /**
         * Resolve each parameter
         */
        $dependencies = [];

        foreach ($parameters as $parameter) {
            // Check if this parameter should be filled with our extra args
            if ($parameter->getName() === '_data' && !empty($args)) {
                $dependencies[] = $args;
                continue;
            }

            $type = $parameter->getType();

            if ($type === null || $type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Cannot resolve parameter {$parameter->getName()} in {$className}");
                }
            } else {
                $dependencies[] = $this->get($type->getName());
            }
        }

        // Create a new instance with resolved dependencies
        $instance = $reflector->newInstanceArgs($dependencies);
        $this->injectDataIfSupported($instance, $args);
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

        if (!is_subclass_of($class, AbstractServiceProvider::class)) {
            throw new \RuntimeException("Service provider {$class} must extend " . AbstractServiceProvider::class);
        }

        /** @var \Framework\DI\AbstractServiceProvider $provider */
        $provider = $this->get($class);

        if (!method_exists($provider, 'register')) {
            throw new \RuntimeException("Service provider {$class} must implement a register method.");
        }

        $provider->register();
    }

    public function findAndLoadServiceProviders(string $directory = __DIR__ . "/../../app/code/Services"): void
    {
        if (!is_dir($directory)) {
            throw new \RuntimeException("Directory {$directory} does not exist.");
        }

        $files = glob($directory . DIRECTORY_SEPARATOR . '*ServiceProvider.php');

        foreach ($files as $index => $file) {
            $files[$index] = realpath($file);
        }

        $className = [];

        foreach ($files as $index => $filePath) {
            require_once $filePath;
            $className[$index] = "App\\Services\\" . pathinfo($filePath, PATHINFO_FILENAME);
            $this->registerProvider($className[$index]);
        }


        foreach ($className as $class) {
            /**
             * @var \Framework\DI\AbstractServiceProvider $instance
             */
            $instance = $this->get($class);
            $instance->boot();
        }

    }
}