<?php

namespace App\Services;

use App\Application;
use Framework\DI\ServiceProvider;
use Psr\Log\LoggerInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register Application as singleton with logger injection
        $this->container->singleton(Application::class, function () {
            $application = new Application($this->container);

            // If Logger is available, inject it into Application
            if (class_exists('Psr\Log\LoggerInterface') && isset($this->container->bindings[LoggerInterface::class])) {
                $logger = $this->container->resolve(LoggerInterface::class);
                // You'll need to add a setLogger method to Application class
                if (method_exists($application, 'setLogger')) {
                    $application->setLogger($logger);
                }
            }

            return $application;
        });
    }
}
