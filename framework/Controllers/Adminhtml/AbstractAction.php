<?php

namespace Framework\Controllers\Adminhtml;

use Framework\Controllers\ActionInterface as BaseActionInterface;
use Framework\Controllers\AbstractAction as BaseAbstractAction;
use Framework\Response\ResponseInterface;

abstract class AbstractAction extends BaseAbstractAction implements BaseActionInterface
{
    /**
     * Execute the action.
     *
     * @return ResponseInterface
     */
    abstract public function execute() : ResponseInterface;

    /**
     * Check if the user is authorized to perform the action.
     *
     * @return bool
     */
    protected function isAuthorized(): bool
    {
        // Here you would typically check if the user has the right permissions
        // For simplicity, we will just return true
        return true;
    }

    /**
     * Get the name of the action.
     *
     * @return string
     */
    public function getName(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * Get the base URL of the application.
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        $env = require_once "../../app.php/config/env.php";
        return $env['base_url'] ?? '';
    }

    public function isAdminRequest(): bool
    {
        // This method can be used to determine if the request is an admin request
        // For now, we will return true as a placeholder
        return true;
    }

    public function getRequestOrigin(): string
    {
        return self::REQUEST_ORIGIN;
    }
}