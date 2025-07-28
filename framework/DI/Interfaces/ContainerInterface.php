<?php

namespace Framework\DI\Interfaces;

interface ContainerInterface
{
    /**
     * Returns the singleton instance of the Container.
     *
     * @return ContainerInterface
     */
    public static function getInstance(): ContainerInterface;

    /**
     * Retrieves an instance of the specified type with optional arguments.
     *
     * @param string $type The type to retrieve.
     * @param array $args Optional arguments to pass to the constructor.
     * @return object The resolved instance.
     * @throws \RuntimeException If the type cannot be resolved.
     */
    public function get(string $type, array $args = []) : object;

    /**
     * Creates a new instance of the specified type with optional arguments.
     *
     * @param string $type The type to create.
     * @param array $args Optional arguments to pass to the constructor.
     * @return object The created instance.
     * @throws \RuntimeException If the type cannot be resolved.
     */
    public function create(string $type, array $args = []) : object;
}