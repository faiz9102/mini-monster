<?php

namespace App\Services;

use Framework\DI\AbstractServiceProvider;

class UserDefinedServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        // Register your user-defined services here
        // Example:
        // $this->container->bind('my_service', function () {
        //     return new MyService();
        // });
    }

    public function boot(): void
    {
        // Code to run after all services are registered
        // Example: $this->container->get('my_service')->initialize();
    }
}