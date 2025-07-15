<?php

namespace App\Services;

use Framework\DI\ServiceProvider;
use Framework\Response\Result\Page;
use Framework\View\Block\Template\Helper as LayoutHelper;
use framework\View\Layout\Layout;
use framework\View\Layout\LayoutInterface;
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

        // Register Layout
        $this->container->singleton(LayoutInterface::class, function () {
            return new Layout($this->container->resolve(Layout::class));
        });

        // Bind interface to concrete implementation
        $this->container->bindInterface(LayoutInterface::class, Layout::class);

        // Register Page
        $this->container->singleton(Page::class, function () {
            return new Page($this->container->resolve(LayoutInterface::class));
        });
    }
}