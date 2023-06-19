<?php

namespace Lighter\Framework\Facades;

class Router
{
    protected ?\Lighter\Framework\Router $router = null;

    /**
     * Set Facade target object instance
     *
     * @return void
     */
    public function setup(): void
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
    public static function get(string $path, callable|array $resolver): void
    {
        (new static())->setUp();
        (new static())->router->get($path, $resolver);
    }
}