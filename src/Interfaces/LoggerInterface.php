<?php

namespace Lighter\Framework\Interfaces;

interface LoggerInterface
{
    /**
     * Log Info
     * @param mixed $data
     * @return void
     */
    public function info(mixed $data): void;

    /**
     * Log Errors
     * @param mixed $data
     * @return void
     */
    public function error(mixed $data): void;

    /**
     * Write To Log
     *
     * @param string $data
     * @param string $type
     * @return void
     */
    public function output(string $data, string $type): void;
}