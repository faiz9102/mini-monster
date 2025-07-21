<?php

namespace App\Services;

use Framework\Application;
use Framework\ConfigProvider;
use Framework\DI\AbstractServiceProvider;
use Framework\Logger\Interfaces\LoggerInterface;
use Framework\Response\Interfaces\ResponseInterface;
use Framework\Response\Result\Page;

class AppServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this-> container->bindInterface(
            ResponseInterface::class,
            Page::class
        );

        $this->container->bind(
            ConfigProvider::class, function () {
                return ConfigProvider::getInstance();
            }
        );

        $this->container->bindSingleton(Application::class, function () {
            return new Application(
                $this->container,
                $this->container->get(LoggerInterface::class)
            );
        });

        $this->container->bindSingleton(
            ConfigProvider::class,
            function () {
                return ConfigProvider::getInstance();
            }
        );
    }

    public function boot(): void
    {
        // Initializing the RequestContext class so its shared instance is available ASAP
        $this->container->get(\Framework\App\RequestContext::class);
    }
}