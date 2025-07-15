<?php
namespace App\Controllers\Index;

use Framework\Controllers\AbstractAction;
use Framework\Response\Result\Page;

class Index extends AbstractAction
{

    /**
     * @var Page
     */
    protected $view;

    /**
     * Index constructor.
     *
     * @param Page $view
     */
    public function __construct(Page $view)
    {
        $this->view = $view;
    }

    /**
     * @return \Framework\Response\ResponseInterface
     */
    public function execute(): \Framework\Response\ResponseInterface
    {
        return $this->view;
    }
}