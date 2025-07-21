<?php

declare(strict_types=1);

namespace Framework\App;

use Framework\DI\Container;
use JetBrains\PhpStorm\NoReturn;
use Psr\Log\LoggerInterface;

/**
 * A bootstrap of application
 *
 * Performs basic initialization root function: injects init parameters and creates DI Container
 * Can create/run applications
 */
class Bootstrap
{

    /**
     * The initialization parameters (normally come from the $_SERVER)
     *
     * @var array
     */
    private array $server;

    /**
     * Root directory
     *
     * @var string
     */
    private readonly string $rootDir;

    /**
     * Object manager
     *
     * @var Container
     */
    private readonly Container $container;

    /**
     * Maintenance mode manager
     *
     * @var MaintenanceMode
     */
    private readonly MaintenanceMode $maintenance;

    /**
     * Constructor
     *
     * @param ?Container $container
     * @param string $rootDir
     * @param array $initParams
     */
    public function __construct($rootDir, array $initParams = [], ?Container $container = null)
    {
        $this->handleGlobalException();
        $this->rootDir = $rootDir;
        $this->server = [...$initParams];

        if ($container === null) {
            // Load the bootstrap file to get a properly configured container
            $bootstrapFile = $rootDir . '/app/bootstrap.php';
            if (file_exists($bootstrapFile)) {
                $this->container = require $bootstrapFile;
            } else {
                // Fallback to basic container if bootstrap file doesn't exist
                $this->container = Container::getInstance();
            }
        } else {
            $this->container = $container;
        }
    }

    /**
     * Gets the current parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->server;
    }

    /**
     * Factory method for creating application instances
     *
     * In case of failure,
     * the application will be terminated by "exit(1)"
     *
     * @param string $type
     * @param array $arguments
     * @return AppInterface | void
     *
     * @throws \InvalidArgumentException
     */
    public function createApplication(string $type, array $arguments = []): ?AppInterface
    {
        try {
            $application = $this->container->get($type, $arguments);
            if (!($application instanceof AppInterface)) {
                throw new \InvalidArgumentException("The provided class doesn't implement AppInterface: {$type}");
            }
            return $application;
        } catch (\Exception $e) {
            // Log the creation failure before terminating
            error_log("Bootstrap createApplication failed for {$type}: " . $e->getMessage());
            $this->terminate($e);
            // This should never be reached due to exit() in terminate(), but adding for completeness
            return null;
        }
    }

    /**
     * Runs an application
     *
     * @param AppInterface $application
     * @return void
     *
     */
    public function run(AppInterface $application)
    {
        try {
            try {
                $this->initErrorHandler();
                $this->assertMaintenance();
                if (!$this->assertInstalled())
                    throw new \Exception('Application is not installed. Please install it first before using.');

                $response = $application->launch();
                $response->send();
            } catch (\Exception $e) {
                $this->container->get(LoggerInterface::class)->error($e->getMessage());
                if (!$application->catchException($this, $e)) {
                    throw $e;
                }
            }
        } catch (\Throwable $e) {
            $this->terminate($e);
        }
    }

    /**
     * Asserts maintenance mode
     *
     * @return void
     * @throws \Exception
     *
     * phpcs:disable Magento2.Exceptions
     */
    protected function assertMaintenance(): void
    {
        $this->maintenance = $this->container->get(MaintenanceMode::class);
        if ($this->maintenance->isOn()) {
            throw new \Exception('Unable to proceed: the maintenance mode is enabled. ');
        }
    }

    /**
     * Determines whether application is installed
     *
     * @return bool
     */
    private function assertInstalled()
    {
        // TODO: implement isInstalled logic
        return true;
    }

    /**
     * Gets the object manager instance
     *
     * @return \Framework\DI\Container
     */
    public function getcontainer()
    {
        return $this->container;
    }

    /**
     * Sets a custom error handler
     *
     * @return void
     */
    private function initErrorHandler()
    {
        // TODO: implement custom error handler
    }

    /**
     * Checks whether developer mode is set in the initialization parameters
     *
     * @return bool
     */
    public function isDeveloperMode() : bool
    {
        return false;
    }

    private function handleGlobalException(): void
    {
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error === null)
                return;
            else
                self::terminateStatic(
                    new \ErrorException(
                        $error['message'],
                        0,
                        1,
                        $error['file'],
                        $error['line']
                    )
                );
        });
    }

    protected function terminate(\Throwable $e)
    {
        // Always use fallback approach for better reliability
        $this->handleTerminationFallback($e);
    }

    #[NoReturn] private static function terminateStatic(\Throwable $e): void
    {
        // Fallback termination logic for static context
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
        // Simplified error response for static context
        echo '<h1>An error occurred</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
        exit(1);
    }

    /**
     * Fallback termination handler when container fails
     */
    private function handleTerminationFallback(\Throwable $e): void
    {
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');

        if ($this->isDeveloperMode() || true) {
            echo $this->generateErrorResponseString($e);
        } else {
            echo $this->generateSimpleErrorResponse();
        }

        exit(1);
    }

    /**
     * Generate simple error response for production
     */
    private function generateSimpleErrorResponse(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; color: #343a40; padding: 20px; }
        .error-container { max-width: 600px; margin: auto; background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #dc3545; }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Service Temporarily Unavailable</h1>
        <p>We're sorry, but the service is temporarily unavailable. Please try again later.</p>
    </div>
</body>
</html>
HTML;
    }

    private function generateErrorResponseString(\Throwable $error): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; color: #343a40; padding: 20px; }
        .error-container { width: 70vw; margin: auto; background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #dc3545; }
        pre { background-color: #f1f1f1; padding: 10px; border-radius: 3px; }   
        code { font-family: monospace; }
        .error-message { margin-bottom: 20px; }
        .error-message strong { color: #dc3545; }
        .error-message p { margin: 0; }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Error : {$error->getCode()}</h1>
        <div class="error-message">
            <strong>{$error->getMessage()} </strong>
            <p>File: {$error->getFile()} (Line: {$error->getLine()})</p>
        </div>
        <pre><code>{$error->getTraceAsString()}</code></pre>
    </div>
    </body>
</html>
HTML;

    }

}