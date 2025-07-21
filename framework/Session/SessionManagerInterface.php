<?php

namespace Framework\Session;
interface SessionManagerInterface
{
    /**
     * Start the session.
     *
     * @return void
     */
    public function start(): void;

    /**
     * Get a session variable by key.
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * Set a session variable by key.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): void;

    /**
     * Remove a session variable by key.
     *
     * @param string $key
     * @return void
     */
    public function remove(string $key): void;

    /**
     * Destroy the session.
     *
     * @return void
     */
    public function destroy(): void;
}
