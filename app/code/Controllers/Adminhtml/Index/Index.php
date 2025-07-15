<?php
declare(strict_types=1);

namespace App\Controllers\Adminhtml\Index;

use Framework\Controllers\Adminhtml\AbstractAction;
use Framework\DI\Container;
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
        /*ob_start();
        $view->render(__DIR__ . '/../../../../../view/template/index/index.phtml', ['name' => 'Faiz']);
        $content = ob_get_clean();

        $view->render(__DIR__ . '/../../../../../view/layout/base/default.phtml', [
            'title' => 'Admin Page',
            'content' => $content
        ]);*/

        return $this->response->setStatusCode()->setBody("");
    }
}