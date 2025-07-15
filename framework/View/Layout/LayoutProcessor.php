<?php

namespace framework\View\Layout;

use App\ConfigProvider;
use Framework\View\Block\Template\Helper as LayoutHelper;

class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @var \framework\View\Layout\LayoutHelper
     */
    protected LayoutHelper $helper;
    /**
     * @var LayoutParser
     */
    protected LayoutParser $parser;

    protected Layout $layout;

    public function __construct(Layout $layout, LayoutHelper $helper, LayoutParser $parser)
    {
        $this->layout = $layout;
        $this->helper = $helper;
        $this->parser = $parser;
    }

    /**
     * Process the layout and return the rendered HTML.
     *
     * @return string
     */
    public function process(): string
    {
        // Get the layout identifier
        $layoutIdentifier = $layout->getIdentifier();

        // Resolve the layout file path based on the identifier
        $layoutFile = $this->resolveLayoutFilePath($layoutIdentifier);

        // Parse the layout file to get the configuration
        $layoutConfig = $this->getLayoutConfig($layoutFile);

        // Set the blocks in the layout
        $layout->setBlocks(...$layoutConfig['blocks']);

        // Render the layout to HTML
        return $layout->render();
    }

    protected function getLayoutConfig(string $layoutFile, $isAdminLayout = false): array
    {
        $config = $this->parser->parse($layoutFile);
        return $config;
    }

    /**
     * Layout file path resolver Based on the URL / layout.identifier.
     *
     * This method can be used to resolve the path of a layout file based on a given identifier.
     * For example, it can be used to convert a layout identifier like 'admin/index/index' into a file path like '/view/adminhtml/index/index.phtml'.
     * @param string $identifier The layout identifier.
     * @return string The resolved file path.
     */
    protected function resolveLayoutFilePath(string $identifier, $isAdminLayout = false): string
    {
        $layoutDirectory = ConfigProvider::getViewDir();

        // Convert the identifier to a file path
        $filePath = str_replace('/', DIRECTORY_SEPARATOR, $identifier);
        $filePath = ConfigProvider::getViewDir() . DIRECTORY_SEPARATOR . $filePath . '.phtml';

        // Check if the file exists
        if (!file_exists($filePath)) {
            throw new \Exception("Layout file not found: " . $filePath);
        }

        return $filePath;
    }

    /**
     * Get the helper instance.
     *
     * @return \framework\View\Layout\LayoutHelper
     */
    public function getHelper(): LayoutHelper
    {
        return $this->helper;
    }
}