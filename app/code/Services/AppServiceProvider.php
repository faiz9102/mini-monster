<?php

namespace App\Services;

use Framework\DI\AbstractServiceProvider;
use App\Application;
use Psr\Log\LoggerInterface;
use Framework\Response\ResponseInterface;
use Framework\Response\Result\Page;
use Framework\Logger\Logger;
use App\ConfigProvider;

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

        $this->container->bind(Application::class, function () {
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
        $this->container->get(\Framework\App\RequestContext::class);
    }
}