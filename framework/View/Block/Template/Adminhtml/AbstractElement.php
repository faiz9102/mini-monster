<?php
declare(strict_types=1);

namespace Framework\View\Block\Template;

class AbstractElement implements BlockElementInterface
{
    private string $name {
        get => $this->name;
        set => $this->name = $value;
    }
    private ?string $template {
        get => $this->template;
        set => $this->template = $value;
    }
    private array $children;

    public function getClildren(): array
    {
        return $this->children;
    }

    public function setChildren(array $children): void
    {
        $this->children = $children;
    }

    private array $_data;

    public function __construct(string $name, ?string $template = null, array $data = [], array children = [])
    {
        $this->name = $name;
        $this->template = $template;
        $this->_data = $data;
        $this->children = $children;
    }

    public function toHtml(): string
    {
        if (empty($this->children)) {

            if (empty($this->_template)) {
                return '';
            }

            ob_start();
            extract($this->_data);
            require $this->_template;
            return ob_get_clean() ?: '';
        }
    }
}