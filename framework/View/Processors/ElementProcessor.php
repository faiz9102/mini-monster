<?php

namespace Framework\View\Processors;

use Framework\DI\Container;
use Framework\View\Block\BlockElementInterface;
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
        $childData = [];

        if (!empty($element['children'])) {
            foreach ($element['children'] as $child) {
                $childData[$child['name']] .= $this->process($child);
            }

            $block = $this->container->create($element['blockClass'], [
                'name'     => $element['name'],
                'template' => $element['template'],
                'data'     => $element['data'] ?? [],
                'children' => $element['children'],
            ]);

            $elementOutput = $block->_toHtml();

            foreach ($childData as $name => $data) {
                $elementOutput = str_replace('<!--' . $name . '-->', $data, $elementOutput);
            }

            return $elementOutput;
        }

        $block = $this->container->create($element['blockClass'], [
            'name'     => $element['name'],
            'template' => $element['template'],
            'data'     => $element['data'] ?? [],
        ]);

        return $block->_toHtml();
    }
}