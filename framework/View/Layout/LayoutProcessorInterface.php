<?php

namespace Framework\View\Layout;

interface LayoutProcessorInterface
{
    /**
     * Render the layout file and return the HTML.
     *
     * @param string $layoutFile The path to the layout file.
     * @param bool $isAdminLayout Whether the layout is for admin area.
     * @return string The rendered HTML of the layout.
     */
    public function render(LayoutInterface $layout): string;

    public function getLayoutFile(LayoutInterface $layout): string;
}