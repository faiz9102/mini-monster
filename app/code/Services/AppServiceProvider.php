<?php

namespace App\Services;

use Framework\DI\ServiceProvider;
use App\Application;
use Psr\Log\LoggerInterface;
use Framework\Logger\Logger;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(Application::class, function () {
            return Application::getInstance(
                $this->container,
                $this->container->resolve(LoggerInterface::class)
            );
        });
    }
}