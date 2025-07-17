<?php

class SessionManager implements SessionManagerInterface
{
    private array $sessionData = [];

    public function start(): void
    {
        // Start the session logic here, e.g., session_start();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function get(string $key)
    {
        return $this->sessionData[$key] ?? null;
    }

    public function set(string $key, $value): void
    {
        $this->sessionData[$key] = $value;
    }

    public function remove(string $key): void
    {
        unset($this->sessionData[$key]);
    }

    public function destroy(): void
    {
        // Destroy the session logic here, e.g., session_destroy();
        session_unset();
        session_destroy();
        $this->sessionData = [];
    }
}