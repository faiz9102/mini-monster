<?php
declare(strict_types=1);

namespace Framework\View\Layout;

use Framework\View\Block\Template\Element as Block;

/**
 * Layout class for managing layout templates and blocks.
 */
class Layout implements LayoutInterface
{
    /**
     * @var Block[] $Blocks
     */
    private array $Blocks = [];
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

    public function getTemplatePath(): string
    {
        // TODO : implement layout file mapping logic
        return '';
    }

    /**
     * Render the layout with all blocks
     *
     * @return string
     */
    public function render(): string
    {
        // Basic implementation - can be expanded as needed
        $output = '';

        // You might want to load the template file here and process it
        $templatePath = $this->getTemplatePath();
        if (file_exists($templatePath)) {
            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }

        // If no template or template doesn't exist, render blocks directly
        if (empty($output)) {
            foreach ($this->Blocks as $block) {
                $output .= $block->toHtml();
            }
        }

        return $output;
    }
}
