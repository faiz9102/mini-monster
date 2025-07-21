<?php

namespace Framework\Services;

use Framework\DI\AbstractServiceProvider;
use Framework\Logger\Interfaces\LoggerInterface;
use Framework\Logger\Logger;
use Monolog\Handler\RotatingFileHandler;

class LogServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->container->bind(LoggerInterface::class, function () {
            $logger = new Logger('app');

            // Add a rotating file handler that stores logs in var/log/app.log
            $logPath = \Framework\FileSystem\BaseFileSystem::getRootPath() . '/var/log/app.log';
            $handler = new RotatingFileHandler($logPath, 0, \Monolog\Level::Debug);
            $logger->pushHandler($handler);

            return $logger;
        });
    }
}
