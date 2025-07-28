<?php

namespace Framework\App\Area;

use Framework\App\Area\Interfaces\AreaManagerInterface;
use Framework\ConfigProvider;
use Framework\Request\Context;

class AreaManager implements AreaManagerInterface
{
    const string AREA_FRONTEND = 'frontend';

    const string AREA_ADMIN = 'adminhtml';

    private string $currentArea;

    private Context $context;

    private ConfigProvider $configProvider;

    /**
     * Initializes the area manager with the given request context and configuration provider.
     *
     * @param Context $context
     * @param ConfigProvider $configProvider
     */
    public function __construct(Context $context, ConfigProvider $configProvider)
    {
        $this->context = $context;
        $this->configProvider = $configProvider;

        // Initialize the current area based on the request context
        $this->currentArea = $this->resolveArea();
    }

    /**
     * @inheritDoc
     */
    public function getArea(): string
    {
        return $this->currentArea;
    }

    /**
     * @inheritDoc
     */
    public function isAdmin(): bool
    {
        return $this->currentArea === self::AREA_ADMIN;
    }

    /**
     * Resolves the area based on the request context.
     *
     * @return string
     */
    private function resolveArea(): string
    {
        $backendConfig = $this->configProvider->get('backend');
        if (!is_null($backendConfig)) {
            $backendFrontName = $backendConfig['frontName'] ?? 'admin';
        }
        $path = trim($this->context->server()->getRequestUri(), '/');
        $pathParts = explode('/', $path);
        $firstPart = $pathParts[0] ?? '';

        if ($firstPart === $backendFrontName) {
            return self::AREA_ADMIN;
        }

        return self::AREA_FRONTEND;
    }
}