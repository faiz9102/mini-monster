<?php

namespace Framework;

use Framework\App\Bootstrap;
use Framework\App\Interfaces\AppInterface;
use Framework\DI\Container;
use Framework\Logger\Interfaces\LoggerInterface;
use Framework\App\Area\Interfaces\AreaManagerInterface;
use Framework\Response\Interfaces\ResponseInterface;
use Framework\Response\Result\Page;

class Application implements AppInterface
{
    private Container $container;
    private ConfigProvider $config;
    private LoggerInterface $logger;

    /**
     * Application constructor
     */
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
        // Get the request context from container
        /** @var AreaManagerInterface $areaManager */
        $areaManager = $this->container->get(AreaManagerInterface::class);

        // Parse URL and determine if we're in admin mode
        $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $pathParts = array_values(array_filter(explode('/', $path)));

        // Check if we're in admin areaManager
        $this->checkAndSetAdminArea($areaManager, $pathParts);

        // Resolve controller and action names
        $controllerName = $pathParts[0] ?? 'index';
        $actionName = $pathParts[1] ?? 'index';

        // Build the controller class name
        $controllerClass = $this->buildControllerClassName($areaManager, $controllerName, $actionName);
        $actionMethod = 'execute';

        // Log route information
        $this->logRouteInformation($controllerClass, $actionMethod, $path, $areaManager->isAdmin());

        // Try to dispatch to the controller
        return $this->dispatchController($controllerClass, $actionMethod, $controllerName, $actionName);
    }

    /**
     * Check if request is for admin area and update request context
     */
    private function checkAndSetAdminArea(AreaManagerInterface $requestContext, array &$pathParts): void
    {
        $backend = $this->config->get('backend', []);
        $frontName = is_array($backend) ? ($backend['frontName'] ?? '') : '';

        if (!empty($pathParts[0]) && $pathParts[0] === $frontName) {
            array_shift($pathParts);
        }
    }

    /**
     * Build controller class name based on request parts
     */
    private function buildControllerClassName(AreaManagerInterface $requestContext, string $controllerName, string $actionName): string
    {
        return 'App\\Controllers\\' .
            ($requestContext->isAdmin() ? 'Adminhtml\\' : '') .
            ucfirst($controllerName) . '\\' . ucfirst($actionName);
    }

    /**
     * Log route dispatch information
     */
    private function logRouteInformation(string $controllerClass, string $actionMethod, string $path, bool $isAdmin): void
    {
        $this->logger->info('Route dispatched', [
            'controller' => $controllerClass,
            'action' => $actionMethod,
            'path' => $path,
            'isAdmin' => $isAdmin
        ]);
    }

    /**
     * Dispatch to controller if exists, or return 404
     */
    private function dispatchController(string $controllerClass, string $actionMethod, string $controllerName, string $actionName): ResponseInterface
    {
        if (class_exists($controllerClass) && method_exists($controllerClass, $actionMethod)) {
            // Let the container create the controller with all dependencies
            /** @var \Framework\Controllers\AbstractAction $controller */
            $controller = $this->container->get($controllerClass);
            $controller->_setData([
                'isAdmin' => true
            ]);

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
     * Handle exceptions that occurred during bootstrap and launch
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