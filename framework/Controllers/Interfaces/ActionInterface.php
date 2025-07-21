<?php
declare(strict_types=1);

namespace Framework\Controllers\Interfaces;

use Framework\Response\Interfaces\ResponseInterface;

interface ActionInterface
{
    public const string REQUEST_ORIGIN = 'frontend';

    /**
     * Execute the action.
     *
     * @return void
     */
    public function execute() : ResponseInterface;

    public function getRequestOrigin() : string;
}