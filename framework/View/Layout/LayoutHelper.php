<?php

namespace framework\View\Layout;
class LayoutHelper
{
    /**
     * @var string
     */
    private string $layoutFile;

    /**
     * @var array
     */
    private array $blocks = [];

    public function __construct(string $layoutFile = "default", array $blocks = [])
    {
        $this->layoutFile = $layoutFile;
        $this->blocks = $blocks;
    }

    public function getLayoutFile(): string
    {
        return $this->layoutFile;
    }

    public function getBlocks(): array
    {
        return $this->blocks;
    }

    public function setBlocks(array $blocks): void
    {
        $this->blocks = $blocks;
    }

    public function addBlock(string $block): void
    {
        $this->blocks[] = $block;
    }
}