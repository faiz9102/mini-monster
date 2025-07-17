<?php

namespace App\Services;

use Framework\DI\ServiceProvider;
use Framework\Response\Result\Page;
use Framework\View\Block\Template\Helper as LayoutHelper;
use Framework\View\Layout\Layout;
use Framework\View\Layout\LayoutInterface;
use Framework\View\Layout\LayoutParser;
use Framework\View\Layout\LayoutProcessor;
use Framework\FileSystem\ViewFileSystem;
use Opis\JsonSchema\Validator;
use App\ConfigProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind(
            Layout::class,
            function () {
                return new Layout();
            }
        );

        $this->container->bindInterface(
            LayoutInterface::class,
            Layout::class
        );
    }
}