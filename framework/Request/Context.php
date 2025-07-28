<?php

namespace Framework\Request;

use Framework\DI\DataInjectable;
use Framework\Request\Interfaces\ContextInterface;
use Framework\Request\Interfaces\GetInterface;
use Framework\Request\Interfaces\PostInterface;
use Framework\Request\Interfaces\ServerInterface;

class Context implements ContextInterface
{
    use DataInjectable;
    private GetInterface $get;
    private PostInterface $post;
    private ServerInterface $server;

    public function __construct(
        GetInterface $get,
        PostInterface $post,
        ServerInterface $server
    ) {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
    }

    public function get(): GetInterface
    {
        return $this->get;
    }

    public function post(): PostInterface
    {
        return $this->post;
    }

    public function server(): ServerInterface
    {
        return $this->server;
    }
}