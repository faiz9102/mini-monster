<?php

namespace Framework\View\Layout\Interfaces;

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
     * Get the templates of the layout.
     *
     * @return string
     */
    public function getTemplate(): string;

    /**
     * Set the templates of the layout.
     *
     * @param string $template
     * @return self
     */
    public function setTemplate(string $template): self;

    /**
     * Get all blocks in the layout.
     *
     * @return array
     */
    public function getBlocks(): array;

    /**
     * @param array $blocks
     * @return self
     */
    public function setBlocks(array $blocks): self;

    /**
     * @param array $block
     * @return self
     */
    public function addBlock(array $block): self;

    /**
     * @param string $blockName
     * @return self
     */
    public function removeBlock(string $blockName): self;
}