<?php

namespace Lighter\Framework;

use Lighter\Framework\Interfaces\ArrayableInterface;

class Request implements ArrayableInterface
{
    public function __construct(protected Router $router)
    {
        $this->router = $router;
    }

    public function setProperties(array $properties): self
    {
        foreach ($properties as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

    public function get(string $key): mixed
    {
        return $this->$key;
    }

    /**
     * Redirect to a given route
     *
     * @param string $path
     * @return mixed
     * @throws \Exception
     */
    public function redirect(string $path): mixed
    {
        return $this->router->redirect($path);
    }

    /**
     * Return Request as array
     *
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}