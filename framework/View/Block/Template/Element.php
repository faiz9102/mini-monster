<?php
declare(strict_types=1);

namespace Framework\View\Block\Template;

use Framework\View\Block\Template\BlockElementInterface;

class Element implements BlockElementInterface
{
    private array $data;

    private array $children;
    private string $name;
    private ?string $template;


    public function __construct(string $name, ?string $template = null, array $data = [], array $children = [])
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
}