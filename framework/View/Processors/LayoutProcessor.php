<?php

namespace Framework\View\Processors;

use Framework\View\Layout\Interfaces\LayoutInterface;
use Framework\View\Processors\Interfaces\ElementProcessorInterface as BlockProcessor;
use Framework\View\Processors\Interfaces\LayoutProcessorInterface;

class LayoutProcessor implements LayoutProcessorInterface
{
    private BlockProcessor $blockProcessor;

    /**
     * LayoutProcessor constructor.
     *
     * @param BlockProcessor $blockProcessor
     */
    public function __construct(
        BlockProcessor $blockProcessor
    ) {
        $this->blockProcessor = $blockProcessor;
    }

    public function process(LayoutInterface $layout): string
    {
        $output = '';

        foreach ($layout->getBlocks() as $block) {
            $output .= $this->blockProcessor->process($block);
        }

        return $output;
    }
}