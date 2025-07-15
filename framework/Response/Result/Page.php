<?php
declare(strict_types=1);

namespace Framework\Response\Result;

use Framework\Response\AbstractResponse;
use Framework\View\Layout\Layout;
use Framework\View\Layout\LayoutInterface;
use Framework\View\Layout\LayoutProcessorInterface;

class Page extends AbstractResponse
{
    public const CONTENT_TYPE = 'text/html';
    private LayoutInterface $layout;
    private LayoutProcessorInterface $layoutProcessor;

    public function __construct(
        Layout $layout,
        string $htmlContent = '',
        int    $statusCode = 200,
                        $headers = [],
    ) {
        $this->layout = $layout;
        parent::__construct($htmlContent, $statusCode, $headers, self::CONTENT_TYPE);
    }

    /**
     * @inheritDoc
     */
    protected function validateBodyContent($content): bool
    {
        // Validate that the content is a valid HTML string
        if (stripos($content, '<!DOCTYPE html>') === false && stripos($content, '<html') === false) {
            return true; // Not a valid HTML document
        }
        return true; // Valid HTML content
    }

    public function send(): void
    {
        // Set the HTTP response code
        http_response_code($this->responseCode);

        // Set the content type header
        if (!empty($this->contentType)) {
            $this->setHeader('Content-Type', $this->contentType);
        }

        // Send all headers
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

//        $htmloutput = $this->layout->render();
//        $this->setBody($htmloutput);

        $htmloutput = "<h1>Hi From Faiz</h1>";
        $this->body? : $this->setBody($htmloutput);
        // Output the body content
        echo $this->getBody();
    }

    private function getPageLayoutCofig()
    {

    }
}