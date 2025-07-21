<?php

namespace Framework\Cookie;

use Framework\Cookie\Interfaces\CookieManagerInterface;

class CookieManager implements CookieManagerInterface
{
    public const DEFAULT_PATH = '/';
    public const DEFAULT_DOMAIN = '';
    public const DEFAULT_EXPIRES = 0; // 0 means until the browser is closed
    public const DEFAULT_SECURE = false;
    public const DEFAULT_HTTP_ONLY = true;

    /**
     * @inheritDoc
     */
    public function setCookie(string $name, string $value, int $expires = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = true): void
    {
        // TODO: Implement setCookie() method.
    }

    /**
     * @inheritDoc
     */
    public function getCookie(string $name): ?string
    {
        // TODO: Implement getCookie() method.
    }

    /**
     * @inheritDoc
     */
    public function deleteCookie(string $name): void
    {
        // TODO: Implement deleteCookie() method.
    }
}