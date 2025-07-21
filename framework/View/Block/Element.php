<?php
declare(strict_types=1);

namespace Framework\View\Block;

use Framework\View\Block\Interfaces\BlockElementInterface;

class Element implements BlockElementInterface
{
    private array $data;

    private array $children;
    private string $name;
    private string $template;

    public function __construct(string $name,string $template , array $data = [], array $children = [])
    {
        $this->name = $name;
        $this->template = $template;
        $this->data = $data;
        $this->children = $children;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function setChildren(array $children): self
    {
        $this->children = $children;
        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): self
    {
        $this->template = $template;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }


    public function isAdminBlock(): bool
    {
        return false;
    }

    public function _toHtml(): string
    {
        $templateFile = \Framework\View\Block\Helper\Data::getTemplatePath($this->template);

        if (!file_exists($templateFile)) {
            throw new \RuntimeException("Template file not found: $templateFile");
        }

        // the data in the data array will come from the layout file
        extract($this->data); // make $data['foo'] become $foo
        $block = $this;

        ob_start();
        require $templateFile;
        return ob_get_clean();
    }
}