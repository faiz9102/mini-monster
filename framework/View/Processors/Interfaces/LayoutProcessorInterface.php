<?php

namespace Framework\View\Processors\Interfaces;

use Framework\View\Layout\LayoutConfig;
use Framework\View\Layout\LayoutInterface;

interface LayoutProcessorInterface
{
    /**
     * Render the layout file and return the HTML.
     *
     * @param LayoutInterface $layout The Page Layout.
     * @return string The rendered HTML of the layout.
     */
    public function process(LayoutInterface $layout) : string;

}