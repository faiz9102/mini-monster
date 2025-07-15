<?php
declare(strict_types=1);

use App\Services\AppServiceProvider;
use App\Services\FileSystemServiceProvider;
use App\Services\LogServiceProvider;
use App\Services\ViewServiceProvider;
use Framework\DI\Container;

$container = Container::getInstance();

// Base config binding
$container->bind('config', function () {
    $configPath = __DIR__ . '/config/env.php';
    return file_exists($configPath) ? require $configPath : [];
});

// Register service providers
$container->registerProvider(LogServiceProvider::class);
$container->registerProvider(FileSystemServiceProvider::class);
$container->registerProvider(ViewServiceProvider::class);
$container->registerProvider(AppServiceProvider::class);

// You can still scan and auto-register additional services if needed
// $container->scanAndRegister(__DIR__ . '/Services', 'App\\Services');

return $container;