<?php

namespace Lighter\Framework\Facades;

class Logger
{
    public static function info($data): void
    {
        (new \Lighter\Framework\Logger\Logger())->info($data);
    }

    public static function error($data): void
    {
        (new \Lighter\Framework\Logger\Logger())->error($data);
    }
}