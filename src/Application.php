<?php

namespace Lighter\Framework;

use Exception;

class Application
{
    protected Request $request;
    protected Router $router;

    public function __construct()
    {
        $this->router = new Router();
        $this->request = new Request($this->router);
    }

    protected static Container $container;

    /**
     * Set up Application
     *
     * @return void
     */
    public static function setup(): void
    {
        // session
        if (!isset($_SESSION)) {
            session_start();
            $_SESSION['_token'] = md5(uniqid(microtime(), true));
        }
    }

    /**
     * Setup and run the application
     *
     * @return mixed
     * @throws Exception
     */
    public static function run(): mixed
    {
        static::setup();

        $router = new Router();

        $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
        $method = $_SERVER['REQUEST_METHOD'];

        return $router->redirect($uri, $method);
    }

    public static function setContainer(Container $container): void
    {
        static::$container = $container;
    }

    public static function bind(string $key, callable $resolver): void
    {
        static::$container->bind($key, $resolver);
    }

    /**
     * @throws Exception
     */
    public static function resolve(string $key)
    {
        return static::$container->resolve($key);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}