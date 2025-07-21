<?php

namespace Framework\View\Processors\Interfaces;

use Framework\View\Layout\Interfaces\LayoutInterface;

interface PageProcessorInterface
{
    /**
     * Process the layout and return the rendered HTML.
     *
     * @param LayoutInterface $layout The layout to process.
     * @return string The rendered HTML of the layout.
     */
    public function process(LayoutInterface $layout): string;
}