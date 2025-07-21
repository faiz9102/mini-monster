<?php
declare(strict_types=1);

use Framework\DI\Container;

$initParams = $_SERVER;

$container = Container::getInstance();

// Base config binding
$container->bind('config', function () {
    $configPath = __DIR__ . '/config/env.php';
    return file_exists($configPath) ? require $configPath : [];
});

// Register service providers
//$container->registerProvider(LogServiceProvider::class);
//$container->registerProvider(FileSystemServiceProvider::class);
//$container->registerProvider(SchemaServiceProvider::class);
//$container->registerProvider(ViewServiceProvider::class);
//$container->registerProvider(AppAbstractServiceProvider::class);

$serviceProviderDirectory = __DIR__ . '/code/Services';

$container->findAndLoadServiceProviders($serviceProviderDirectory);

return $container;