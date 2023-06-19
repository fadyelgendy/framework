<?php

namespace Lighter\Framework;

use Exception;

class Application
{
    protected Request $request;
    protected Router $router;

    public function __construct()
    {
        $this->router = Router::getInstance();
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

        $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
        $method = $_SERVER['REQUEST_METHOD'];

        return (new static())->router->redirect($uri, $method);
    }

    /**
     * Set App Container
     *
     * @param Container $container
     * @return void
     */
    public static function setContainer(Container $container): void
    {
        static::$container = $container;
    }

    /**
     * Bind Object for container
     *
     * @param string $key
     * @param callable $resolver
     * @return void
     */
    public static function bind(string $key, callable $resolver): void
    {
        static::$container->bind($key, $resolver);
    }

    /**
     * Resolve An object
     *
     * @param string $key
     * @return mixed
     * @throws Exception
     */
    public static function resolve(string $key)
    {
        return static::$container->resolve($key);
    }
}