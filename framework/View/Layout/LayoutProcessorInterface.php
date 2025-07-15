<?php

namespace framework\View\Layout;

interface LayoutProcessorInterface
{
    /**
     * Render the layout file and return the HTML.
     *
     * @param string $layoutFile The path to the layout file.
     * @param bool $isAdminLayout Whether the layout is for admin area.
     * @return string The rendered HTML of the layout.
     */
    public function renderLayout(string $layoutFile, bool $isAdminLayout = false): string;

    /**
     * Process the layout and return the rendered HTML.
     *
     * @return string
     */
    public function process(): string;

    /**
     * Get the layout configuration.
     *
     * @param string $layoutFile The path to the layout file.
     * @param bool $isAdminLayout Whether the layout is for admin area.
     * @return array The layout configuration as an associative array.
     */
    public function getLayoutConfig(string $layoutFile, bool $isAdminLayout = false): array;
}