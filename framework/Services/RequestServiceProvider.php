<?php

namespace Framework\Services;

use Framework\DI\AbstractServiceProvider;
use Framework\Request\Context;
use Framework\Request\Interfaces\{ContextInterface, GetInterface, PostInterface, ServerInterface};
use Framework\Request\Pool\{Get, Post, Server};

class RequestServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->container->bindInterface(
            ContextInterface::class,
            Context::class
        );

        $this->container->bindInterface(
            GetInterface::class,
            Get::class
        );

        $this->container->bindInterface(
            PostInterface::class,
            Post::class
        );

        $this->container->bindInterface(
            ServerInterface::class,
            Server::class
        );

        $this->container->bindSingleton(
            Get::class,
            function () {
                return new Get();
            }
        );

        $this->container->bindSingleton(
            Post::class,
            function () {
                return new Post();
            }
        );

        $this->container->bindSingleton(
            Server::class,
            function () {
                return new Server();
            }
        );

        $this->container->bindSingleton(
            Context::class,
            function () {
                return new Context(
                    $this->container->get(GetInterface::class),
                    $this->container->get(PostInterface::class),
                    $this->container->get(ServerInterface::class)
                );
            }
        );
    }

    public function boot(): void
    {
    }
}