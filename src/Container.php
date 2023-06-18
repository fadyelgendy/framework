<?php

namespace Lighter\Framework;

use Exception;

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
            throw new Exception("ERROR: Can Not resolve {$key}");
        }

        return call_user_func(static::$bindings[$key]);
    }
}