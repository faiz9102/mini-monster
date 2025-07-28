<?php

namespace Framework\App\Area\Interfaces;

use Framework\Request\Context;
use Framework\ConfigProvider;

interface AreaManagerInterface
{
    /**
     * Initializes the area manager with the given request context and configuration provider.
     *
     * @param ContextInterface $Context
     * @param ConfigProvider $configProvider
     */
    public function __construct(Context $Context, ConfigProvider $configProvider);

    /**
     * Returns the current area based on the request context.
     *
     * @return string
     */
    public function getArea(): string;

    /**
     * Returns true if the current request is for an admin URL,
     * and configured Admin front name. if No admin front name is configured,
     * it defaults to 'admin'.
     *
     * @return bool
     */
    public function isAdmin(): bool;
}