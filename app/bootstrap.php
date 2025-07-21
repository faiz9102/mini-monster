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

$frameworkServiceProvidersDirectory = __DIR__ . '/../framework/Services';
$userDefinedProvidersDirectory = __DIR__ . '/code/Services';

$container->findAndLoadServiceProviders($frameworkServiceProvidersDirectory, 'Framework\Services');
$container->findAndLoadServiceProviders($userDefinedProvidersDirectory, 'App\Services');

$container->bootContainer();

return $container;