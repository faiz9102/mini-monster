<?php

namespace Framework\Response\Interfaces;

interface ResponseInterface
{
    /**
     * Set the HTTP status code for the response.
     *
     * @param int $code
     * @return self
     */
    public function setStatusCode(int $code): self;

    /**
     * Set a header for the response.
     *
     * @param string $name
     * @param string $value
     * @return self
     */
    public function setHeader(string $name, string $value): self;

    /**
     * Set multiple headers for the response.
     *
     * @param array $headers
     * @return self
     */

    public function setHeaders(array $headers): self;

    /**
     * set the content type for the response.
     *
     * @param string $contentType
     * @return self
     */

    public function setContentType(string $contentType): self;

    /**
     * Set the body content for the response.
     *
     * @param string $content
     * @return self
     */

    public function setBody(string $content): self;

    /**
     * Get the body content for the response.
     *
     * @return string
     */
    public function getBody(): string;


    /**
     * Send the response to the client.
     *
     * @return void
     */
    public function send(): void;

    public function clearHeaders() : void;
}