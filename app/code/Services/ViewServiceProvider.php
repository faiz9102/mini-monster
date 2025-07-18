<?php

namespace App\Services;

use Framework\DI\AbstractServiceProvider;
use Framework\Response\Result\Page;
use Framework\Schema\SchemaFacade;
use Framework\View\Block\Template\Helper as LayoutHelper;
use Framework\View\Layout\Layout;
use Framework\View\Layout\LayoutInterface;
use Framework\View\Layout\LayoutParser;
use Framework\View\Layout\LayoutProcessor;
use Framework\FileSystem\ViewFileSystem;
use Framework\View\Layout\LayoutProcessorInterface;
use Opis\JsonSchema\Validator;
use App\ConfigProvider;

class ViewServiceProvider extends AbstractServiceProvider
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
            LayoutProcessorInterface::class,
            LayoutProcessor::class
        );

        $this->container->bindInterface(
            LayoutInterface::class,
            Layout::class
        );
    }
}