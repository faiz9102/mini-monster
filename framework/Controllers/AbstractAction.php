<?php
declare(strict_types=1);

namespace Framework\Controllers;

use Framework\Controllers\ActionInterface as BaseActionInterface;
use Framework\DI\DataInjectable;
use Framework\Response\ResponseInterface;

abstract class AbstractAction implements BaseActionInterface
{
    use DataInjectable;

    /**
     * Execute the action.
     *
     * @return void
     */
    abstract public function execute() : ResponseInterface;

    /**
     * Get the name of the action.
     *
     * @return string
     */
    public function getName(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
    public function getBaseUrl(): string
    {
        $env = require_once "../../app/config/env.php";
        return $env['base_url'] ?? '';
    }

    public function isAdminRequest(): bool
    {
        return false;
    }

    public function getRequestOrigin(): string
    {
        return self::REQUEST_ORIGIN;
    }
}