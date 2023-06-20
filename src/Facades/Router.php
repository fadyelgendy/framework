<?php

namespace Lighter\Framework\Facades;

class Router
{
    protected \Lighter\Framework\Router $router;

    public function __construct()
    {
           $this->router = \Lighter\Framework\Router::getInstance();
    }

    /**
     * Get request
     *
     * @param string $path
     * @param callable|array $resolver
     * @return \Lighter\Framework\Router
     */
    public static function get(string $path, callable|array $resolver): \Lighter\Framework\Router
    {
        return (new static())->router->get($path, $resolver);
    }

    /**
     * Post Request
     *
     * @param string $path
     * @param callable|array $resolver
     * @return \Lighter\Framework\Router
     */
    public static function post(string $path, callable|array $resolver): \Lighter\Framework\Router
    {
        return (new static())->router->post($path, $resolver);
    }

    /**
     * Routes
     *
     * @return array
     */
    public static function routes(): array
    {
        return (new static())->router->routes();
    }
}