<?php

namespace Framework\Response;

use Framework\Response\Interfaces\ResponseInterface;

abstract class AbstractResponse implements ResponseInterface
{
    public int $responseCode;
    protected array $headers = [];

    protected string $contentType = '';
    protected string $body = '';

    public function __construct(
        int $responseCode = 200,
        array $headers = [],
        string $contentType = 'text/plain',
    ) {
        $this->responseCode = $responseCode;
        $this->headers = $headers;
        $this->contentType = $contentType;
    }

    /**
     * @inheritDoc
     */
    public function setStatusCode(int $code = 200): self
    {
       $this->responseCode = $code;
       return $this;
    }

    /**
     * @inheritDoc
     */
    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setHeaders(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->Header[$name] = $value;
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setContentType(string $contentType): self
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setBody(string $content = ''): self
    {
        if($this->validateBodyContent($content)) {
            $this->body = $content;
        } else {
            throw new \InvalidArgumentException('Invalid body content provided.');
        }
        return $this;
    }

    /**
     * Validate the body content.
     *
     * @param string $content
     * @return bool
     */
    abstract protected function validateBodyContent($content): bool;

    /**
     * Get the body content.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
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

        // Output the body content
        echo $this->getBody();
    }

    public function clearHeaders(): void
    {
        header_remove();
    }
}