<?php

namespace Framework\View\Layout;

use App\ConfigProvider;
use Framework\View\Block\Template\Helper as LayoutHelper;
use Framework\FileSystem\ViewFileSystem;
use Framework\Schema\SchemaFacade;

class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @var LayoutParser
     */
    protected LayoutParser $parser;

    /**
     * @var ViewFileSystem
     */
    protected ViewFileSystem $viewFileSystem;

    /**
     * @var SchemaFacade $validator
     */
    protected SchemaFacade $validator;

    /**
     * @var LayoutHelper
     */
    protected LayoutHelper $helper;

    public function __construct(
        LayoutParser $parser,
        ViewFileSystem $viewFileSystem,
        SchemaFacade $validator,
        LayoutHelper $helper
    ) {
        $this->parser = $parser;
        $this->viewFileSystem = $viewFileSystem;
        $this->validator = $validator;
        $this->helper = $helper;
    }

    /**
     * Process the layout and return the rendered HTML.
     *
     * @return string
     */
    public function process(): string
    {
        // Get the layout identifier
        $layoutIdentifier = "";

        // Resolve the layout file path based on the identifier
        $layoutFile = $this->resolveLayoutFilePath($layoutIdentifier);

        // Parse the layout file to get the configuration
        $layoutConfig = $this->getLayoutConfig($layoutFile);

        // Set the blocks in the layout

        // Render the layout to HTML
        return "";
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
     * @return \Framework\View\Layout\LayoutHelper
     */
    public function getHelper(): LayoutHelper
    {
        return $this->helper;
    }

    public function render(LayoutInterface $layout): string
    {
        // Get the layout file based on the layout identifier
        $layoutFile = $this->getLayoutFile($layout);

        // Check if the layout file exists
        if (file_exists($layoutFile)) {
            $layoutConfig = $this->parser->parse($layoutFile);
        }
        return '';
    }

    /**
     * Get the layout file based on the layout identifier.
     * layout identifier is in the format 'area:controllerName_ActionName'. (default)
     * area can be 'adminhtml', 'frontend' or empty for default area.
     * which should resolve to 'view/layout/area/controllername_actioname.json'.
     *
     * @param string $layoutIdentifier
     * @return string
     */
    public function getLayoutFile(LayoutInterface $layout): string
    {
        $layoutInfo = self::getLayoutInfo($layout->getName());
        $viewPath = ViewFileSystem::getViewPath();

        $layoutFilePath = $viewPath . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR
            . $layoutInfo['area'] . DIRECTORY_SEPARATOR
            . $layoutInfo['filePath'] . '.json';

        return $layoutFilePath;
    }

    public static function getLayoutInfo(string $layoutIdentifier): array
    {
        $parts = explode(PATH_SEPARATOR, $layoutIdentifier);
        $partsCount = count($parts);

        if ($partsCount < 1 || $partsCount > 2) {
            throw new \InvalidArgumentException("Layout identifier must be in the format 'area:controllerName_actionName'.");
        }

        if ($partsCount === 2) {
            $area = strtolower($parts[0]) ?: 'base';
            $identifier = $parts[1];
        } else {
            $area = 'base';
            $identifier = $parts[0];
        }

        $fileNameParts = explode('_', $identifier);
        $filePathComponents = array_map(function ($component) {
            return strtolower(trim($component));
        }, $fileNameParts);
        $filePath = implode("_", $filePathComponents);

        return [
            'area' => $area,
            'filePath' => $filePath,
            'parts' => $parts,
            'partsCount' => $partsCount,
            'filePathComponents' => $filePathComponents,
        ];
    }
}