<?php

namespace Lighter\Framework\Facades;

class Router
{
    protected static ?\Lighter\Framework\Router $router;

    /**
     * Set Facade target object instance
     *
     * @return void
     */
    public static function setup(): void
    {
        if (! static::$router)
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
        static::setUp();
        self::$router->get($path, $resolver);
    }
}