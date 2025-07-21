<?php

namespace Framework\View\Processors\Interfaces;

interface ElementProcessorInterface
{

    /**
     * Generates HTML output for a given element.
     * This method processes the element's data and its children.
     * and returns the rendered HTML as a string.
     *
     * @param array $element
     * @return string
     */
    public function process(array $element): string;
}