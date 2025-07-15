<?php
declare(strict_types=1);

namespace App\Controllers\Adminhtml\Index;

use Framework\Controllers\Adminhtml\AbstractAction;
use Framework\Response\ResponseInterface;
use Framework\Response\Result\Page;

class Index extends AbstractAction
{
    private Page $response;
    public function __construct(Page $page)
    {
        $this->response = $page;
    }

    /**
     * Execute the action and return a response.
     *
     * @return ResponseInterface
     */
    public function execute() : ResponseInterface
    {
        // Render the admin index page
        return $this->response->setStatusCode()->setBody("");
    }
}