<?php

namespace Framework\DI;

abstract class AbstractServiceProvider
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