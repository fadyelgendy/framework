<?php

namespace Lighter\Framework;

use Exception;
use Lighter\Framework\Interfaces\ArrayableInterface;
use Lighter\Framework\Interfaces\RequestInterface;

class Request implements ArrayableInterface, RequestInterface
{
    protected array $headers = [];

    protected array $query = [];

    protected array $body = [];

    protected array $files = [];

    protected string $method = "";

    protected string $uri = "";

    protected static ?Request $instance = null;

    public static function getInstance(): ?Request
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Set Request Properties
     *
     * @param array $properties
     * @return $this
     *
     */
    public function setProperties(array $properties): self
    {
        foreach ($properties as $key => $value) {
            $this->$key = $value;
            $this->body[$key] = $value;
        }

        return $this;
    }

    /**
     * Return Request Body, Files
     *
     * @param string|null $key
     * @return array
     */
    public function body(string $key = null): array
    {
        if (!is_null($key)) {
            return $this->get($key);
        }

        return $this->body;
    }

    /**
     * Return request Files
     *
     * @return array
     */
    public function files(): array
    {
        return $this->files;
    }

    /**
     * Set Request query
     *
     * @param string $query
     * @return void
     */
    public function setQuery(string $query): void
    {
        $query = explode('&', $query);

        foreach ($query as $entry) {
            $exploded = explode('=', $entry);
            $this->query[$exploded[0] ?? null] = $exploded[1] ?? null;
        }
    }

    /**
     * Get Query
     *
     * @param string|null $key
     * @return array|string|null
     */
    public function query(string $key = null): array|string|null
    {
        if (is_null($key)) {
            return $this->query;
        }

        if (!array_key_exists($key, $this->query)) {
            return null;
        }

        return $this->query['$key'];
    }

    /**
     * Set Header
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function setHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    /**
     * get Header by key
     *
     * @param string $key
     * @return string|null
     */
    public function header(string $key): string|null
    {
        return $this->headers[$key] ?? null;
    }

    /**
     * Return Headers
     *
     * @return array
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Get Single request property
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->$key;
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

    /**
     * Prepare request
     *
     * @return void
     */
    public function prepare(): void
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $parsed = parse_url($_SERVER['REQUEST_URI']);
        $this->uri = $parsed['path'];

        # Body and Files
        if ($this->method == 'POST') {
            $this->setProperties($_POST);

            if (count($_FILES) > 0) {
                $this->setProperties($_FILES);
                $this->files = $_FILES;
            }
        }

        # Query
        if ($this->method == 'GET') {
            if (array_key_exists('query', $parsed)) $this->setQuery($parsed['query']);
        }

        # Headers
        $url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $headers = apache_request_headers();
        if ($headers) {
            $this->headers = $headers;
        }
    }
}