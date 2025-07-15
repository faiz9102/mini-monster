<?php

namespace Framework\Response;

interface ResponseInterface
{
    /**
     * Set the HTTP status code for the response.
     *
     * @param int $code
     * @return void
     */
    public function setStatusCode(int $code): self;

    /**
     * Set a header for the response.
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setHeader(string $name, string $value): self;

    /**
     * Set multiple headers for the response.
     *
     * @param array $headers
     * @return void
     */

    public function setHeaders(array $headers): self;

    /**
     * set the content type for the response.
     *
     * @param string $contentType
     * @return void
     */

    public function setContentType(string $contentType): self;

    /**
     * Set the body content for the response.
     *
     * @param string $content
     * @return void
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
}