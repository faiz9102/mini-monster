<?php
declare(strict_types=1);

namespace Framework\View\Layout;

/**
 * Layout class for managing layout templates and blocks.
 */
class Layout implements LayoutInterface
{
    /**
     * @var array $_Blocks
     */
    private array $_Blocks = [];
    private string $_name;
    private string $_template;

    public function __construct(string $name = '', string $template = '', array $blocks = [])
    {
        $this->_name = $name;
        $this->_template = $template;
        $this->_Blocks = $blocks;
    }

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
        return $this->_Blocks;
    }

    /**
     * @inheritDoc
     */
    public function removeBlock(string $blockName): LayoutInterface
    {
        foreach ($this->_Blocks as $key => $block) {
            if ($block['name'] === $blockName) {
                unset($this->_Blocks[$key]);
                break;
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addBlock(array $block): LayoutInterface
    {
        $this->_Blocks[$block['name']] = $block;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setBlocks(array $blocks): LayoutInterface
    {
        $this->_Blocks = $blocks;
        return $this;
    }
}
