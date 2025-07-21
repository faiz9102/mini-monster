<?php

namespace Framework\DI;

use Framework\DI\Interfaces\ServiceProviderInterface;

abstract class AbstractServiceProvider implements ServiceProviderInterface
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    abstract public function register(): void;

    public function boot(): void
    {
        // This method can be overridden by subclasses to perform actions after all services are registered.
    }
}