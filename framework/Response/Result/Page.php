<?php
declare(strict_types=1);

namespace Framework\Response\Result;

use Framework\App\Area\Interfaces\AreaManagerInterface;
use Framework\ConfigProvider;
use Framework\Response\AbstractResponse;
use Framework\View\Layout\Interfaces\LayoutInterface;
use Framework\View\Processors\Interfaces\PageProcessorInterface;

class Page extends AbstractResponse
{
    public const string CONTENT_TYPE = 'text/html';
    private LayoutInterface $layout;
    private PageProcessorInterface $pageProcessor;

    private AreaManagerInterface $requestContext;

    private ConfigProvider $config;

    public function __construct(
        LayoutInterface        $layout,
        PageProcessorInterface $pageProcessor,
        AreaManagerInterface   $areaManager,
        ConfigProvider         $config,
        int                    $statusCode = 200,
        array                  $headers = [],
    )
    {
        $this->config = $config;
        $this->pageProcessor = $pageProcessor;
        $this->layout = $layout;
        $this->contentType = self::CONTENT_TYPE;
        $this->requestContext = $areaManager;
        parent::__construct($statusCode, $headers, self::CONTENT_TYPE);
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

        // Resolve the layout name based on the request context Before processing the page
        if (empty($this->layout->getName())) {
            $layoutName = $this->resolveLayoutName();
            $this->layout->setName($layoutName);
        }

        // Process the layout and get the HTML output
        $htmlOutput = $this->pageProcessor->process($this->layout);
        $this->body ?: $this->setBody($htmlOutput);
        echo $this->body;
    }

    public function resolveLayoutName(): string
    {
        // Base layout area
        $layoutName = $this->requestContext->isAdmin() ? 'adminhtml' : 'frontend';

        $layoutName .= ':';

        // Get admin front name from config
        $backend = $this->config->get('backend', []);
        $adminIdentifier = is_array($backend) ? ($backend['frontName'] ?? '') : '';

        // Get current URI
        $uri = $_SERVER["REQUEST_URI"] ?? '/';

        // Remove query string
        $uri = parse_url($uri, PHP_URL_PATH);

        // Normalize the URI by trimming admin path
        if (!empty($adminIdentifier) && str_starts_with($uri, '/' . $adminIdentifier)) {
            $uri = substr($uri, strlen('/' . $adminIdentifier));
        }

        $uri = trim($uri, '/'); // remove leading/trailing slashes

        // Split parts
        $requestParts = explode('/', $uri);

        // Ensure at least controller + action
        if (count($requestParts) < 2) {
            if ($requestParts[0] === '')
                $requestParts[0] = 'index';
            $requestParts = array_pad($requestParts, 2, 'index');
        }


        // Compose layout name (e.g., adminhtml_index_index)
        $layoutName .= implode('_', $requestParts);

        return $layoutName;
    }
}