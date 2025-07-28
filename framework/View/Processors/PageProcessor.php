<?php

namespace Framework\View\Processors;

use Framework\Request\Context;
use Framework\Schema\SchemaFacade;
use Framework\View\Layout\Helper\Data as LayoutHelper;
use Framework\View\Layout\Interfaces\LayoutInterface;
use Framework\View\Processors\Interfaces\LayoutProcessorInterface;
use Framework\View\Processors\Interfaces\PageProcessorInterface;
use Framework\App\Area\Interfaces\AreaManagerInterface;

class PageProcessor implements PageProcessorInterface
{
    const string SCHEMA_NAME = 'layout';

    private LayoutProcessorInterface $layoutProcessor;

    private SchemaFacade $schemaFacade;

    private Context $requestContext;

    private AreaManagerInterface $areaManager;

    public function __construct(
        LayoutProcessorInterface $layoutProcessor,
        SchemaFacade             $schemaFacade,
        AreaManagerInterface      $areaManager,
        Context $requestContext
    )
    {
        $this->requestContext = $requestContext;
        $this->layoutProcessor = $layoutProcessor;
        $this->areaManager = $areaManager;
        $this->schemaFacade = $schemaFacade;
    }

    public function process(LayoutInterface $layout): string
    {
        // Get the layout file based on the layout identifier
        $layoutFile = LayoutHelper::getLayoutFile($layout->getName(),$this->areaManager->isAdmin(),false);



        $fileData = LayoutHelper::parse($layoutFile);

        $layoutConfig = $fileData[self::SCHEMA_NAME];
        $this->populateLayout($layout, $layoutConfig, $layoutFile);

        $output = $this->layoutProcessor->process($layout);

        $output = <<<HTML
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>{$layout->getName()}</title>
            </head>
            <body>
                {$output}
            </body>
        HTML;

        return $output;
    }

    /**
     * Prepares the layout by populating it with blocks based on the layout configuration
     * for further processing by the layout processor.
     *
     * @param LayoutInterface $layout
     * @param array $layoutConfig
     * @return void
     * @throws \InvalidArgumentException
     */
    private function populateLayout(LayoutInterface $layout, array $layoutConfig, string $layoutFile): void
    {
        foreach ($layoutConfig['components'] as $componentConfig) {

            switch ($componentConfig['type']) {
                case 'block':
                    $block = $this->buildBlock($componentConfig);
                    $layout->addBlock($block);
                    break;
                default:
                    throw new \InvalidArgumentException("Unsupported \"type\" in layout: {$componentConfig['type']} in file $layoutFile");
            }
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
        string $blockClass,
        array  $children = [],
        array  $data = [],
    ): array
    {
        if (!class_exists($blockClass)) {
            throw new \RuntimeException("Block class not found: " . $blockClass . " for block: " . $name);
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
        $layoutFile = LayoutHelper::getLayoutFile($layoutIdentifier);

        try {
            $this->schemaFacade->loadFrameworkSchema();
            $layoutConfig = $this->schemaFacade->validateAndReturnContent($layoutFile, self::SCHEMA_NAME);
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to get layout config: " . $e->getMessage());
        }


        return $layoutConfig;
    }
}
