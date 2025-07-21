<?php

namespace Framework\View\Processors;

use Framework\DI\Container;
use Framework\View\Processors\Interfaces\ElementProcessorInterface;

class ElementProcessor implements ElementProcessorInterface
{
    /**
     * @var Container $container
     */
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function process(array $element): string
    {
        $childBlocks = [];
        $childHtml = [];

        if (!empty($element['children'])) {
            foreach ($element['children'] as $child) {
                // Recursively instantiate child blocks
                $childBlock = $this->container->create($child['blockClass'], [
                    'name' => $child['name'],
                    'template' => $child['template'],
                    'data' => $child['data'] ?? [],
                    'children' => $child['children'] ?? [],
                ]);
                $childBlocks[$child['name']] = $childBlock;
                $childHtml[$child['name']] = $this->process($child);
            }

            /** @var \Framework\View\Block\Element $block */
            $block = $this->container->create($element['blockClass'], [
                'name' => $element['name'],
                'template' => $element['template'],
                'data' => $element['data'] ?? [],
                'children' => $childBlocks, // Pass child block instances
            ]);

            $elementOutput = $block->_toHtml();
            $unresolved = [];
            foreach ($childHtml as $name => $data) {
                $placeholder = '<!--' . $name . '-->';
                if (str_contains($elementOutput, $placeholder)) {
                    $elementOutput = str_replace($placeholder, $data, $elementOutput);
                } else {
                    $unresolved[] = $name;
                }
            }
            if (!empty($unresolved)) {
                throw new \RuntimeException(
                    'Unresolved Child Blocks: ' . implode(', ', $unresolved) .
                    ' in Block: ' . $element['name']
                );
            }
            return $elementOutput;
        }

        $block = $this->container->create($element['blockClass'], [
            'name' => $element['name'],
            'template' => $element['template'],
            'data' => $element['data'] ?? [],
            'children' => [],
        ]);

        return $block->_toHtml();
    }
}