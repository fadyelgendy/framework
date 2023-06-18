<?php

namespace Lighter\Framework;

use Exception;
use ReflectionException;

class Router
{
    protected static array $routes = [];

    /**
     * Make a GET request
     *
     * @param string $path
     * @param array $options
     * @return self
     */
    public static function get(string $path, array $options): self
    {
        static::add($path, $options, 'GET');

        return new static();
    }

    /**
     * Make a POST request
     *
     * @param string $path
     * @param array $options
     * @return self
     */
    public static function post(string $path, array $options): self
    {
        static::add($path, $options, 'POST');

        return new static();
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
        if (!array_key_exists($uri, self::$routes)) {
            throw new Exception("ERROR: no route found for {$uri}");
        }

        $route = static::$routes[$uri];

        # Controller Not Exists
        if (!class_exists($route['controller'])) {
            throw new Exception("ERROR: {$route['controller']} is not defined!");
        }

        $controller = new $route['controller']();

        # Action methods not defined
        if (!method_exists($controller, $route['action'])) {
            throw new Exception("ERROR: {$route['action']} is not defined in {$route['controller']}");
        }

        return $controller->{$route['action']}(...$this->resolveDependencies($route));
    }

    /**
     * Add a given route to routes
     *
     * @param string $path
     * @param array $options
     * @param string $method
     * @return void
     */
    protected static function add(string $path, array $options, string $method): void
    {
        static::$routes[$path] = [
            'controller' => $options[0],
            'action' => $options[1],
            'method' => $method
        ];
    }

    /**
     * Resolve route action dependencies
     * @param array $route
     * @return array
     * @throws ReflectionException
     * @throws Exception
     */
    protected function resolveDependencies(array $route): array
    {
        // Resolve
        $reflection = new \ReflectionMethod($route['controller'], $route['action']);
        $params = [];

        foreach($reflection->getParameters() as $parameter) {
            $class = "\App\Core\\" . ucwords($parameter->getName());
            $params[] = Application::resolve($class);
        }

        return $params;
    }
}