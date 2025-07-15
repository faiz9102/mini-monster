<?php
declare(strict_types=1);

namespace Framework\View\Block\Template\Adminhtml;
use Framework\View\Block\Template\BlockElementInterface;

class Element implements BlockElementInterface
{
    public string $name { get => $this->name; set => $this->name = $value; }
    public string $template { get => $this->template; set (string $value) => $this->template = $value; }

    protected array $_data;

    public function __construct(string $name ,?string $template = null, array $data = [])
    {
        $this->name = $name;
        $this->template = $template;
        $this->_data = $data;
    }

    public function toHtml() : string
    {
        if (empty($this->_template)) {
            return '';
        }
        ob_start();
        extract($this->_data);
        require $this->_template;
        return ob_get_clean()? : '';
    }
}