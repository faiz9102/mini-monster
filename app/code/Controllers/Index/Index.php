<?php
namespace App\Controllers\Index;

use Framework\Controllers\AbstractAction;
use Framework\Response\ResponseInterface;
use Framework\Response\Result\Page;

class Index extends AbstractAction
{
    /**
     * @var Page
     */
    protected $page;

    /**
     * Index constructor.
     *
     * @param Page $view
     */
    public function __construct(Page $view)
    {
        $this->page = $view;
    }

    /**
     * @return ResponseInterface
     */
    public function execute(): ResponseInterface
    {
        return $this->page;
    }
}