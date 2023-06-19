<?php

namespace Lighter\Framework\Interfaces;

interface ArrayableInterface
{
    /**
     * To array
     *
     * @return array <Tkey, Tvalue>
     */
    public function toArray(): array;
}