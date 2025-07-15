<?php

namespace App\Services;

use Framework\DI\ServiceProvider;
use Framework\Response\Result\Page;
use Framework\View\Block\Template\Helper as LayoutHelper;
use Framework\View\Layout\Layout;
use Framework\View\Layout\LayoutInterface;
use Framework\View\Layout\LayoutParser;
use Framework\View\Layout\LayoutProcessor;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register LayoutHelper
        $this->container->singleton(LayoutHelper::class, function () {
            return new LayoutHelper();
        });

        // Register LayoutParser
        $this->container->singleton(LayoutParser::class, function () {
            return new LayoutParser();
        });

        // Register LayoutProcessor
        $this->container->singleton(LayoutProcessor::class, function () {
            return new LayoutProcessor(
                $this->container->resolve(LayoutHelper::class),
                $this->container->resolve(LayoutParser::class)
            );
        });

        // Register Layout - fixed to avoid circular dependency
        $this->container->singleton(LayoutInterface::class, function () {
            return new Layout();
        });

        // Bind interface to concrete implementation
        $this->container->bindInterface(LayoutInterface::class, Layout::class);

        // Register Page
        $this->container->singleton(Page::class, function () {
            return new Page($this->container->resolve(LayoutInterface::class));
        });
    }
}