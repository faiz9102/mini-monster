<?php

namespace App\Services;

use Framework\DI\ServiceProvider;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register a PSR-3 compatible logger as a singleton
        $this->container->singleton(LoggerInterface::class, function () {
            $logDir = __DIR__ . '/../../../var/log';

            // Create log directory if it doesn't exist
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            $logger = new Logger('app');

            // Add daily rotating file handler (keeps 7 days of logs)
            $logger->pushHandler(new RotatingFileHandler(
                $logDir . '/application.log',
                7,
                Logger::DEBUG
            ));

            // Add a separate error log for higher-level issues
            $logger->pushHandler(new StreamHandler(
                $logDir . '/error.log',
                Logger::ERROR
            ));

            return $logger;
        });
    }
}
