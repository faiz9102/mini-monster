<?php

namespace App\Services;

use Framework\DI\AbstractServiceProvider;
use Framework\View\Layout\Interfaces\LayoutInterface;
use Framework\View\Layout\Layout;
use Framework\View\Processors\ElementProcessor;
use Framework\View\Processors\Interfaces\ElementProcessorInterface;
use Framework\View\Processors\Interfaces\LayoutProcessorInterface;
use Framework\View\Processors\Interfaces\PageProcessorInterface;
use Framework\View\Processors\LayoutProcessor;
use Framework\View\Processors\PageProcessor;

class ViewServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->container->bindInterface(
            ElementProcessorInterface::class,
            ElementProcessor::class
        );
        $this->container->bind(
            Layout::class,
            function () {
                return new Layout();
            }
        );

        $this->container->bindInterface(
            PageProcessorInterface::class,
            PageProcessor::class
        );

        $this->container->bindInterface(
            LayoutProcessorInterface::class,
            LayoutProcessor::class
        );

        $this->container->bindInterface(
            LayoutInterface::class,
            Layout::class
        );
    }
}