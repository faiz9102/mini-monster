<?php

namespace Framework\Request;

class RequestContext
{
    private bool $isAdmin;

    public function __construct(bool $isAdmin = false)
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * Check if the request is for an admin URL.
     *
     * @return bool
     */
    public function isAdmin(): bool {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }
}