<?php

namespace App;

use Framework\App\AppInterface;
use Framework\App\Bootstrap;
use Framework\DI\Container;
use Framework\Response\ResponseInterface;
use Framework\Response\Result\Page;
use Psr\Log\LoggerInterface;

class Application implements AppInterface
{
    private Container $container;
    private ConfigProvider $config;
    private LoggerInterface $logger;

    public function __construct(Container $container, LoggerInterface $logger)
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

    /**
     * Launch application
     *
     * @return ResponseInterface
     */
    public function launch(): ResponseInterface
    {
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
            $controller = $this->container->get($controllerClass);
            return $controller->$actionMethod();
        } else {
            if ($this->logger) {
                $this->logger->warning("Route not found", [
                    'controller' => $controllerClass,
                    'action' => $actionMethod
                ]);
            }
            
            // Return a 404 response
            $response = $this->container->get(Page::class);
            $response->setStatusCode(404);
            $response->setBody("$controllerClass | $controllerName - $actionName not found.");
            return $response;
        }
    }

    /**
     * Ability to handle exceptions that may have occurred during bootstrap and launch
     *
     * @param Bootstrap $bootstrap
     * @param \Exception $exception
     * @return bool
     */
    public function catchException(Bootstrap $bootstrap, \Exception $exception): bool
    {
        // Log exceptions if logger is available
        if ($this->logger) {
            $this->logger->error('Application error', [
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
        }

        // For now, let Bootstrap handle the exception
        return false;
    }
}