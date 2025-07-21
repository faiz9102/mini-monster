<?php

namespace Framework\Cookie\Interfaces;
interface CookieManagerInterface
{
    /**
     * Set a cookie.
     *
     * @param string $name
     * @param string $value
     * @param int $expires
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return void
     */
    public function setCookie(string $name, string $value, int $expires = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = true): void;

    /**
     * Get a cookie value by name.
     *
     * @param string $name
     * @return string|null
     */
    public function getCookie(string $name): ?string;

    /**
     * Delete a cookie by name.
     *
     * @param string $name
     * @return void
     */
    public function deleteCookie(string $name): void;
}
