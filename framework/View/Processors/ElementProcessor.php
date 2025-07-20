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
        if (!empty($element['children'])) {
            $childrenOutput = '';
            foreach ($element['children'] as $child) {
                $childrenOutput .= $this->process($child);
            }

            $block = $this->container->create($element['blockClass'], [
                'name'     => $element['name'],
                'template' => $element['template'],
                'data'     => $element['data'] ?? [],
                'children' => $element['children'],
            ]);

            return $block->_toHtml();
        }

        $block = $this->container->create($element['blockClass'], [
            'name'     => $element['name'],
            'template' => $element['template'],
            'data'     => $element['data'] ?? [],
        ]);

        return $block->_toHtml();
    }


    /**
     * @inheritDoc
     */
    public function renderBlock(BlockElementInterface $block, $config): void
    {
        if ($block->getTemplate()) {
            $block->setTemplate($config['template'] ?? $block->getTemplate());
        }
    }
}