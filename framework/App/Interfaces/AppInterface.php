<?php

namespace Framework\App\Interfaces;

use Framework\App;
use Framework\Response\Interfaces\ResponseInterface;

interface AppInterface
{
    /**
     * Default application locale
     */
    const DISTRO_LOCALE_CODE = 'en_US';

    /**
     * Launch application
     *
     * @return ResponseInterface
     */
    public function launch() : ResponseInterface;

    /**
     * Ability to handle exceptions that may have occurred during bootstrap and launch
     *
     * Return values:
     * - true: exception has been handled, no additional action is needed
     * - false: exception has not been handled - pass the control to Bootstrap
     *
     * @param \Framework\App\Bootstrap $bootstrap
     * @param \Exception $exception
     * @return bool
     */
    public function catchException(App\Bootstrap $bootstrap, \Exception $exception) : bool;
}