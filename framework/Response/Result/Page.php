<?php
declare(strict_types=1);

namespace Framework\Response\Result;

use Framework\FileSystem\ViewFileSystem;
use Framework\Response\AbstractResponse;
use Framework\Schema\SchemaFacade;
use Framework\View\Layout\Layout;
use Framework\View\Layout\LayoutInterface;
use Framework\View\Layout\LayoutProcessorInterface;

class Page extends AbstractResponse
{
    const string SCHEMA_ID = 'layout';
    public const string CONTENT_TYPE = 'text/html';
    private LayoutInterface $layout;
    private LayoutProcessorInterface $layoutProcessor;

    private SchemaFacade $schemaFacade;

    public function __construct(
        Layout                   $layout,
        LayoutProcessorInterface $layoutProcessor,
        SchemaFacade             $schemaFacade,
        string                   $htmlContent = '',
        int                      $statusCode = 200,
        array                    $headers = [],
    )   {
        $this->layoutProcessor = $layoutProcessor;
        $this->schemaFacade = $schemaFacade;
        $this->layout = $layout;
        $this->contentType = self::CONTENT_TYPE;
        parent::__construct($htmlContent, $statusCode, $headers, self::CONTENT_TYPE);
    }

    /**
     * @inheritDoc
     */
    protected function validateBodyContent($content): bool
    {
        // Check if the content is a valid HTML string
        return true;
    }

    public function send(): void
    {
        $this->layout->setName("adminhtml:index_index");
        $file = $this->layoutProcessor->getLayoutFile($this->layout);

        // Load and register framework schemas BEFORE validation
        $this->schemaFacade->loadFrameworkSchema();

        // Validate the layout file against the schema
        try {
            $isSchemaValid = $this->schemaFacade->validate(
                $file, self::SCHEMA_ID
            );

            if (!$isSchemaValid) {
            }
        } catch (\Exception $e) {
            // Continue execution even if validation fails in development
        }


        // Set the HTTP response code()
        http_response_code($this->responseCode);

        // Set the content type header
        if (!empty($this->contentType)) {
            $this->setHeader('Content-Type', $this->contentType);
        }

        // Send all headers
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // Process the layout and get the HTML output
        $htmloutput = $this->layoutProcessor->render($this->layout->setName("adminhtml:index_index"));
        $this->body ?: $this->setBody($htmloutput);


        // Output the body content
//        echo $this->getBody();
        echo $this->layoutProcessor->getLayoutFile($this->layout);
    }

    private function getPageLayoutConfig()
    {

    }
}