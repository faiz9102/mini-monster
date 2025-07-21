<?php

namespace Framework\View\Processors\Interfaces;

use Framework\View\Block\BlockElementInterface as BlockElementInterface;

interface ElementProcessorInterface
{

    public function process(array $element): string;
}