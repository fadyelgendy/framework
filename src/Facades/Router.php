<?php

namespace Lighter\Framework\Facades;

class Router
{
    protected static ?\Lighter\Framework\Router $router = null;

    public function __construct()
    {
        static::$router = new \Lighter\Framework\Router();
    }

    /**
     * Get request
     *
     * @param string $path
     * @param callable|array $resolver
     * @return void
     */
    public static function get(string $path, callable|array $resolver): void
    {
        static::$router->get($path, $resolver);
    }
}