<?php
declare(strict_types=1);
error_reporting(E_ALL);
date_default_timezone_set('UTC');
ini_set('precision', 14);
ini_set('serialize_precision', 14);

define('BP', realpath(__DIR__ . '/..'));

require __DIR__ . '/../vendor/autoload.php';

use Framework\App\Bootstrap;
use Framework\Application;

/**
 * Bootstrap the application using Magento-like class-based bootstrapping.
 * This file is the entry point for the application.
 * It creates a Bootstrap instance, creates the Application, and runs it.
 * All error handling and responses are managed by the Bootstrap class.
 *
 * @package App
 * @author Muhammad Faiz
 * @license MIT
 */

$rootDir = BP;
$initParams = $_SERVER;

// Create Bootstrap instance
$bootstrap = new Bootstrap($rootDir, $initParams);

// Create Application instance through Bootstrap
$application = $bootstrap->createApplication(Application::class);
$bootstrap->run($application);