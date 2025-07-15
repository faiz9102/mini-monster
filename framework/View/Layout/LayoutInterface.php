<?php

namespace Framework\View\Layout;

use Framework\View\Block\Template\Element as Block;

interface LayoutInterface
{
    /**
     * Get the name of the layout.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set the name of the layout.
     *
     * @param string $name
     * @return self
     */
    public function setName(string $name): self;

    /**
     * Get the template of the layout.
     *
     * @return string
     */
    public function getTemplate(): string;

    /**
     * Set the template of the layout.
     *
     * @param string $template
     * @return self
     */
    public function setTemplate(string $template): self;

    /**
     * Get all blocks in the layout.
     *
     * @return Block[]
     */
    public function getBlocks(): array;

    /**
     * Get the path to the template file for this layout.
     *
     * @return string
     */
    public function getTemplatePath(): string;
}