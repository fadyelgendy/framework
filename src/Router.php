<?php

namespace Lighter\Framework;

use Exception;
use ReflectionException;

class Router
{
    protected array $routes = [];
    protected array $params = [];

    protected static ?Router $instance = null;

    public static function getInstance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Make a GET request
     *
     * @param string $path
     * @param callable|array $resolver
     * @return self
     */
    public function get(string $path, callable|array $resolver): self
    {
        static::add($path, $resolver, 'GET');

        return new static();
    }

    /**
     * Make a POST request
     *
     * @param string $path
     * @param callable|array $resolver
     * @return self
     */
    public function post(string $path, callable|array $resolver): self
    {
        $this->add($path, $resolver, 'POST');

        return new static();
    }

    /**
     * Return routes array
     *
     * @return array
     */
    public function routes(): array
    {
        return $this->routes;
    }

    /**
     * Redirect to a given route
     *
     * @param string $uri
     * @param string $method
     * @return mixed
     *
     * @throws Exception
     */
    public function redirect(string $uri, string $method = 'GET'): mixed
    {
        # Route Not Exists
        if (!array_key_exists($uri, $this->routes)) {
            throw new Exception("ERROR: no route found for {$uri}");
        }

        $route = $this->routes[$uri];

        # Method doesn't match
        if ($route['method'] != $method) {
            abort(new Exception("ERROR: {$route['method']} doesn't match {$method}"), 500);
        }

        # Resolve Route dependencies
        $this->resolveDependencies($route);

        // Callable
        if (array_key_exists('resolver', $route)) {
            return call_user_func($route['resolver'], ...$this->params);
        }

        # Controller Not Exists
        if (!class_exists($route['controller'])) {
            throw new Exception("ERROR: {$route['controller']} is not defined!");
        }

        $controller = new $route['controller']();

        # Action methods not defined
        if (!method_exists($controller, $route['action'])) {
            throw new Exception("ERROR: {$route['action']} is not defined in {$route['controller']}");
        }

        return $controller->{$route['action']}(...$this->params);

    }

    /**
     * Add a given route to routes
     *
     * @param string $path
     * @param callable|array $resolver
     * @param string $method
     * @return void
     */
    protected function add(string $path, callable|array $resolver, string $method): void
    {
        if (is_callable($resolver)) {
            $this->routes[$path] = [
                'resolver' => $resolver,
                'method' => $method
            ];
            return;
        }

        $this->routes[$path] = [
            'controller' => $resolver[0],
            'action' => $resolver[1],
            'method' => $method
        ];
    }

    /**
     * Resolve route action dependencies
     * @param array $route
     * @return void
     * @throws ReflectionException
     * @throws Exception
     */
    protected function resolveDependencies(array $route): void
    {
        $this->params = Application::container()->resolveRouteDependencies($route);
    }
}