<?php


namespace Framework\Request\Interfaces;

interface ContextInterface
{
    public function get(): GetInterface;

    public function post(): PostInterface;

    public function server(): ServerInterface;
}