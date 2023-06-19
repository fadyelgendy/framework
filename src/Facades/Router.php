<?php

namespace Lighter\Framework\Facades;

class Router
{
    protected \Lighter\Framework\Router $router;

    public function __construct()
    {
        $this->router = new \Lighter\Framework\Router();
    }

    /**
     * Get request
     *
     * @param string $path
     * @param callable|array $resolver
     * @return void
     */
    public static function get(string $path, callable|array $resolver)
    {
        (new static())->router->get($path, $resolver);
    }
}