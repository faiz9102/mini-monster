<?php
declare(strict_types=1);

namespace Framework\View\Block\Adminhtml;

use Framework\View\Block\Element as BaseElement;

class Element extends BaseElement
{
    public function isAdminBlock(): bool
    {
        return true;
    }
}