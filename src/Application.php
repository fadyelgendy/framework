<?php

namespace Lighter\Framework;

use Exception;

class Application
{
    protected Request $request;
    protected Router $router;
    protected static ?Application $instance = null;

    protected static Container $container;

    public function __construct()
    {
        $this->request = Request::getInstance();
        $this->router = Router::getInstance();
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function request(): Request
    {
        return $this->request;
    }

    /**
     * Singleton for app
     *
     * @return Application
     */
    public static function getInstance(): Application
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Set up Application
     *
     * @return void
     */
    public static function setup(): void
    {
        if (!isset($_SESSION)) {
            session_cache_limiter('private');
            session_cache_expire(10);
            session_start();
            $_SESSION['_token'] = md5(uniqid(microtime(), true));
        }

        # populate Request
        (new static())->request->prepare();
    }

    /**
     * Setup and run the application
     *
     * @return void
     * @throws Exception
     */
    public static function run(): void
    {
        static::setup();

        $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
        $method = $_SERVER['REQUEST_METHOD'];

        echo (new static())->router->redirect($uri, $method);
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
     * Return container
     *
     * @return Container
     */
    public static function container(): Container
    {
        return static::$container;
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
    public static function resolve(string $key): mixed
    {
        return static::$container->resolve($key);
    }
}