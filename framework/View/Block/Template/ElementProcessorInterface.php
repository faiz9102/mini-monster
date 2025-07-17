<?php

namespace Framework\View\Block\Template;

interface ElementProcessorInterface
{
    /**
     * Process the element and return its HTML.
     *
     * @param BlockElementInterface $element
     * @return string
     */
    public function process(BlockElementInterface $element): string;

    /**
     * Set the template for the element.
     *
     * @param string $template
     * @return void
     */
    public function setTemplate(string $template): void;
}