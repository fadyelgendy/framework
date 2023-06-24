<?php

namespace Lighter\Framework;

use Exception;
use Lighter\Framework\Facades\Logger;
use ReflectionException;

class Container
{
    protected static array $bindings = [];

    public function bind(string $key, callable $resolver): void
    {
        static::$bindings[$key] = $resolver;
    }

    /**
     * @throws Exception
     */
    public function resolve(string $key)
    {
        if (! array_key_exists($key, static::$bindings)) {
            $exception = new Exception("ERROR: Can Not resolve {$key}");
            Logger::error($exception);
            throw $exception;
        }

        return call_user_func(static::$bindings[$key]);
    }

    /**
     * Resolve Route Dependencies
     *
     * @param array $route
     * @return array
     * @throws ReflectionException
     * @throws Exception
     */
    public function resolveRouteDependencies(array $route): array
    {
        $params = [];

        # Resolver os callable type
        if (array_key_exists('resolver', $route)) {
            $reflection = new \ReflectionFunction($route['resolver']);
        } else {
            # Resolver is tuple type [controller, action]
            $reflection = new \ReflectionMethod($route['controller'], $route['action']);
        }

        # Resolve dependencies
        foreach ($reflection->getParameters() as $parameter) {
            if (is_object($parameter)) {
                $class = $parameter->getType()->getName();
                $params[] = $this->resolve($class);
            }
        }

        return $params;
    }
}