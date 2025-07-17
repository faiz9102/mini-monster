<?php
declare(strict_types=1);

namespace Framework\Response\Result;

use Framework\Response\AbstractResponse;
use Framework\View\Layout\Layout;
use Framework\View\Layout\LayoutInterface;
use Framework\View\Layout\LayoutProcessorInterface;
use Framework\Schema\SchemaFacade;
use Opis\JsonSchema\Uri;

class Page extends AbstractResponse
{
    public const string CONTENT_TYPE = 'text/html';
    private LayoutInterface $layout;
    private LayoutProcessorInterface $layoutProcessor;

    public function __construct(
        Layout $layout,
//        LayoutProcessorInterface $layoutProcessor,
        string $htmlContent = '',
        int    $statusCode = 200,
                        $headers = [],
    ) {
//        $this->layoutProcessor = $layoutProcessor;
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
        $facade = SchemaFacade::getInstance();
        $loader = $facade->getLoader();
        $loader->loadFrameworkSchema();
        $helper = $facade->getHelper();
        $schemas = $helper->diccoverSchemaFromFrameworkDir();
        $uri = null;

        foreach ($schemas as $schema => $file) {
            $SchemaUri = [
                'scheme' => '',
                "id" => $schema . "#",
                'user' => null,
                'pass' => null,
                'host' => null,
                'port' => null,
                'path' => $file,
                'query' => null,
                'fragment' => null,
            ];
            $uri = new Uri($SchemaUri);

            $loader->resolver()->registerFile("layout", $file);
        }
        $file = $loader->resolver()->resolve($uri);





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

        // Process the layout and get the HTML output
        $htmloutput = $this->layoutProcessor->render($this->layout->setName("adminhtml:index_index"));
        $this->body? : $this->setBody($htmloutput);


        // Output the body content
//        echo $this->getBody();
        echo $this->layoutProcessor->getLayoutFile($this->layout->getName());
    }

    private function getPageLayoutCofig()
    {

    }
}