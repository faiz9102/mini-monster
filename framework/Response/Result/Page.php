<?php
declare(strict_types=1);

namespace Framework\Response\Result;

use Framework\Response\AbstractResponse;
use framework\View\Layout\LayoutInterface;
use framework\View\Layout\LayoutProcessorInterface;

class Page extends AbstractResponse
{
    public const CONTENT_TYPE = 'text/html';
    private LayoutInterface $layout;
    private LayoutProcessorInterface $layoutProcessor;

    public function __construct(
        LayoutInterface $layout, // Use interface consistently
        string $htmlContent = '',
        int    $statusCode = 200,
                        $headers = [],
    ) {
        $this->layout = $layout;
        parent::__construct($htmlContent, $statusCode, $headers, self::CONTENT_TYPE);
    }

    public function getLayout(): LayoutInterface
    {
        return $this->layout;
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

        $htmloutput = $this->layout->render();
//        $this->setBody($htmloutput);

        $this->body? : $this->setBody($htmloutput);
        // Output the body content
        echo $this->getBody();
    }

    private function getPageLayoutCofig()
    {

    }
}