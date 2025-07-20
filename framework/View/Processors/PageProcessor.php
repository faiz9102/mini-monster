<?php

namespace Framework\View\Processors;

use Framework\App\RequestContext;
use Framework\DI\Container;
use Framework\FileSystem\ViewFileSystem;
use Framework\Schema\SchemaFacade;
use Framework\View\Layout\LayoutInterface;
use Framework\View\Layout\LayoutParser;
use Framework\View\Processors\Interfaces\LayoutProcessorInterface;
use Framework\View\Processors\Interfaces\PageProcessorInterface;

class PageProcessor implements PageProcessorInterface
{
    const string SCHEMA_ID = 'layout';

    private LayoutProcessorInterface $layoutProcessor;

    private SchemaFacade $schemaFacade;

    private RequestContext $requestContext;

    private Container $container;

    public function __construct(
        LayoutProcessorInterface $layoutProcessor,
        SchemaFacade             $schemaFacade,
        RequestContext           $requestContext,
        Container                $container
    )
    {
        $this->requestContext = $requestContext;
        $this->layoutProcessor = $layoutProcessor;
        $this->schemaFacade = $schemaFacade;
        $this->container = $container;
    }

    public function process(LayoutInterface $layout): string
    {
        // Get the layout file based on the layout identifier
        $layoutFile = $this->getLayoutFile($layout->getName());

        // Check if the layout file exists
        if (file_exists($layoutFile)) {
            $fileData = LayoutParser::parse($layoutFile);
        } else {
            throw new \RuntimeException("Layout file not found: " . $layoutFile);
        }

        $layoutConfig = $fileData[self::SCHEMA_ID];
        $this->populateLayout($layout, $layoutConfig);

        $output = $this->layoutProcessor->process($layout);

        $output = <<<HTML
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>{$layoutFile}</title>
                <!-- Add any additional head elements here -->
            </head>
            <body>
                {$output}
                <!-- Add any additional body elements here -->
            </body>
        HTML;

        return $output;
    }

    public function populateLayout(LayoutInterface $layout, array $layoutConfig): void
    {
        foreach ($layoutConfig['components'] as $componentConfig) {
            if ($componentConfig['type'] !== 'block') {
                continue;
            }

            $block = $this->buildBlock($componentConfig);
            $layout->addBlock($block);
        }
    }

    /**
     * Recursively builds a block and its children.
     */
    private function buildBlock(array $config): array
    {
        $name = $config['name'];
        $template = $config['template'] ?? '';
        $data = $config['arguments']['data'] ?? [];
        $blockClass = $config['class'] ?? $config['blockClass'] ?? null;

        // Step 1: Parse children from both 'children' and 'arguments.children'
        $childConfigs = [];

        // Children in 'children' array
        if (!empty($config['children']) && is_array($config['children'])) {
            foreach ($config['children'] as $child) {
                $childConfigs[] = $child;
            }
        }

        // Children in 'arguments.children' named map
        if (!empty($config['arguments']['children']) && is_array($config['arguments']['children'])) {
            foreach ($config['arguments']['children'] as $namedChild) {
                $childConfigs[] = $namedChild;
            }
        }

        // Step 2: Recursively build child blocks
        $children = [];
        foreach ($childConfigs as $childConfig) {
            if ($childConfig['type'] !== 'block') {
                continue;
            }
            $children[] = $this->buildBlock($childConfig);
        }

        // Step 3: Create this block with resolved children
        return $this->generateBlock($name, $template, $blockClass, $children, $data);
    }

    private function generateBlock(
        string $name,
        string $template,
        ?string $blockClass,
        array  $children = [],
        array  $data = [],
    ): array
    {
        if ($blockClass === null) {
            $blockClass = 'Framework\\View\\Block\\Element';
        }

        if (!class_exists($blockClass)) {
            throw new \RuntimeException("Block class not found: " . $blockClass);
        }

        return $block = [
            'name' => $name,
            'template' => $template,
            'blockClass' => $blockClass,
            'children' => $children,
            'data' => $data,
        ];
    }

    public function getLayoutConfig(string $layoutIdentifier): array
    {
        $layoutFile = $this->getLayoutFile($layoutIdentifier);

        try {
            $this->schemaFacade->loadFrameworkSchema();
            $layoutConfig = $this->schemaFacade->validateAndReturnContent($layoutFile, self::SCHEMA_ID);
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to get layout config: " . $e->getMessage());
        }


        return $layoutConfig;
    }

    /**
     * Get the layout file based on the layout name.
     * layout name is in the format 'area:controllerName_ActionName'. (default)
     * area can be 'adminhtml', 'frontend' or empty for default area.
     * which should resolve to 'view/layout/area/controllername_actioname.json'.
     *
     * @param string $layoutIdentifier
     * @return string
     */
    public function getLayoutFile(string $layoutName): string
    {
        $layoutInfo = self::getLayoutInfo($layoutName);
        $viewPath = ViewFileSystem::getViewPath();

        return $viewPath . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR
            . $layoutInfo['area'] . DIRECTORY_SEPARATOR
            . $layoutInfo['filePath'] . '.json';
    }

    public static function getLayoutInfo(string $layoutName): array
    {
        $parts = explode(PATH_SEPARATOR, $layoutName);
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
