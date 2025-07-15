<?php
declare(strict_types=1);

namespace framework\View\Layout;

use Framework\View\Block\Template\Element as Block;

/**
 * Layout class for managing layout templates and blocks.
 */
class Layout implements LayoutInterface
{
    /**
     * @var Block[] $Blocks
     */
    private array $_Blocks = [];
    private string $_name;
    private string $_template;

    public function getName(): string
    {
        return $this->_name;
    }
    public function setName(string $name): self
    {
        $this->_name = $name;
        return $this;
    }

    public function getTemplate(): string
    {
        return $this->_template;
    }

    public function setTemplate(string $template): self
    {
        $this->_template = $template;
        return $this;
    }

    public function getBlocks(): array
    {
        return $this->Blocks;
    }

    public function setBlocks(Block ...$Blocks): self
    {
        $this->Blocks = $Blocks;
        return $this;
    }

    public function addBlock(Block $block): self
    {
        $this->Blocks[] = $block;
        return $this;
    }

    public function getTemplatePath(): string
    {
        // TODO : implement layout file mapping logic
        return '';
    }
}
}
