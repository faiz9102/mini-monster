<?php

namespace Framework\Logger;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Psr\Log\LoggerInterface;

class Logger extends MonologLogger implements LoggerInterface
{
    public function __construct(string $name = 'app')
    {
        parent::__construct($name);

        // Try to create log file handler, fallback to stdout only
        $logDir = __DIR__ . '/../../var/log';
        if (!is_dir($logDir)) {
            try {
                mkdir($logDir, 0755, true);
            } catch (\Exception $e) {
                // Fallback to a writable directory if /var/log creation fails
                $logDir = __DIR__ . '/../../var/log';
                if (!is_dir($logDir)) {
                    mkdir($logDir, 0755, true);
                }
                error_log("Warning: error occured in creating the var/log directory" . $e->getMessage());
            }
        }

        // Always add stdout handler
        $this->pushHandler(new StreamHandler('php://stdout', Level::Debug));
    }

    public function logError(\Throwable $exception, array $context = []): void
    {
        $this->error($exception->getMessage(), array_merge([
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ], $context));
    }

    public function logRequest(string $method, string $uri, array $params = []): void
    {
        $this->info('HTTP Request', [
            'method' => $method,
            'uri' => $uri,
            'params' => $params,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    }

    public function logQuery(string $sql, array $bindings = [], ?float $time): void
    {
        $this->debug('Database Query', [
            'sql' => $sql,
            'bindings' => $bindings,
            'execution_time' => $time ? round($time, 4) . 'ms' : null
        ]);
    }
}