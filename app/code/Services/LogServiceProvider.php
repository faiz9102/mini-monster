<?php

namespace App\Services;

use Framework\DI\ServiceProvider;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(LoggerInterface::class, function () {
            $logger = new Logger('app');

            // Add a rotating file handler that stores logs in var/log/app.log
            $logPath = __DIR__ . '/../../../var/log/app.log';
            $handler = new RotatingFileHandler($logPath, 0, Logger::DEBUG);
            $logger->pushHandler($handler);

            return $logger;
        });
    }
}
