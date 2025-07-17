<?php
declare(strict_types=1);

namespace Framework\View\Block\Template\Adminhtml;

use Framework\View\Block\Template\Element as BaseElement;

class Element extends BaseElement
{
    public function isAdminBlock(): bool
    {
        return true;
    }
}