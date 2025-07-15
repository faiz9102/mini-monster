<?php

namespace Framework\View\Block\Template;

interface BlockElementInterface
{
    /**
     * Returns the Html of the Block Element.
     *
     * @return string
     */
    public function toHtml(): string;
}