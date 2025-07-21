<?php

namespace Framework\DI\Interfaces;

interface ServiceProviderInterface
{
    /**
     * Registers services or bindings in the DI container.
     * This method is called during the bootstrapping process of the application.
     *
     * @return void
     */
    public function register(): void;

    /**
     * Called after all services are registered.
     * At this point, all ServiceProvider Register methods have been called,
     *
     * @return void
     */
    public function boot(): void;
}