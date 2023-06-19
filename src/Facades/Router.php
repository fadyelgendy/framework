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
     * @return void
     */
    public static function get(string $path, callable|array $resolver): void
    {
        (new static())->router->get($path, $resolver);
    }
}