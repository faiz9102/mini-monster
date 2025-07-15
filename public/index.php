<?php
declare(strict_types=1);
error_reporting(E_ALL);
date_default_timezone_set('UTC');
ini_set('precision', 14);
ini_set('serialize_precision', 14);

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE])) {
        http_response_code(500);
        echo "Something went horribly wrong.";
        // log($error); etc.
    }
});

require __DIR__ . '/../vendor/autoload.php';

/**
 * Bootstrap the application and resolve the Application instance.
 * This file is the entry point for the application.
 * It initializes the DI container, loads configuration, and runs the application.
 *
 *
 * @package App
 * @author Muhammad Faiz
 * @license MIT
 * @var Container $container
 */
$container = require __DIR__ . '/../app/bootstrap.php';

use App\Application;
use Framework\DI\Container;


try {// Resolve Application from container
    $app = $container->resolve(Application::class);
    $app->run();
} catch (Exception $e) {
    echo <<<_HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; color: #343a40; padding: 20px; }
        .error-container { max-width: 600px; margin: auto; background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #dc3545; }
        pre { background-color: #f1f1f1; padding: 10px; border-radius: 3px; }   
        code { font-family: monospace; }
        .error-message { margin-bottom: 20px; }
        .error-message strong { color: #dc3545; }
        .error-message p { margin: 0; }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Error</h1>
        <div class="error-message">
            <strong>{$e->getMessage()}</strong>
            <p>File: {$e->getFile()} (Line: {$e->getLine()})</p>
        </div>
        <pre><code>{$e->getTraceAsString()}</code></pre>
    </div>
    </body>
</html>
_HTML;
} finally {

}