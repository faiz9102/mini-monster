<?php

namespace App;

use Framework\DI\Container;
use Psr\Log\LoggerInterface;

class Application
{
    private static ?Application $instance = null;
    private Container $container;

    private ConfigProvider $config;

    private LoggerInterface $logger;

    private function __construct(Container $container, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->container = $container;
        $this->config = ConfigProvider::getInstance();
    }

    /**
     * Set a PSR-3 logger instance
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;
        return $this;
    }

    public function run()
    {
        try {
            $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
            $parts = array_values(array_filter(explode('/', $path)));

            $backend = $this->config->get('backend', []);
            $frontName = is_array($backend) ? ($backend['frontName'] ?? '') : '';
            if (isset($parts[0]) && $parts[0] === $frontName) {
                array_shift($parts);
                $isAdminUrl = true;
            }

            $controllerName = $parts[0] ?? 'index';
            $actionName = $parts[1] ?? 'index';

            $controllerClass = 'App\\Controllers\\' .
                (isset($isAdminUrl) && $isAdminUrl ? 'Adminhtml\\' : '') .
                ucfirst($controllerName) . '\\' . ucfirst($actionName);
            $actionMethod = 'execute';

            // Log route information if logger is available
            if ($this->logger) {
                $this->logger->info('Route dispatched', [
                    'controller' => $controllerClass,
                    'action' => $actionMethod,
                    'path' => $path,
                    'isAdmin' => isset($isAdminUrl) && $isAdminUrl
                ]);
            }

            if (class_exists($controllerClass) && method_exists($controllerClass, $actionMethod)) {
                // Let the container create the controller with all dependencies
                $controller = $this->container->resolve($controllerClass);
                $response = $controller->$actionMethod();

                $response->send();
            } else {
                http_response_code(404);
                if ($this->logger) {
                    $this->logger->warning("Route not found", [
                        'controller' => $controllerClass,
                        'action' => $actionMethod
                    ]);
                }
                echo "$controllerClass | $controllerName - $actionName not found.";
            }
        } catch (\Exception $e) {
            // Log exceptions if logger is available
            if ($this->logger) {
                $this->logger->error('Application error', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
            }
            throw $e; // Re-throw to let the global handler catch it
        }
    }

    public static function getInstance(
        Container       $container,
        LoggerInterface $logger
    ): Application
    {
        if (self::$instance === null) {
            self::$instance = new self($container, $logger);
        }
        return self::$instance;
    }
}