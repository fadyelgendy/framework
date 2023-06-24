<?php

namespace Lighter\Framework\Interfaces;

use Lighter\Framework\Request;

interface RequestInterface
{
    public static function getInstance(): ?Request;

    public function setProperties(array $properties): self;

    public function get(string $key): mixed;

    public function toArray(): array;
}
