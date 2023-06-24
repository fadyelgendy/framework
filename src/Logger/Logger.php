<?php

namespace Lighter\Framework\Logger;

class Logger implements \Lighter\Framework\Interfaces\LoggerInterface
{
    /**
     * @inheritDoc
     */
    public function info(mixed $data): void
    {
        $this->output($data, 'info');
    }

    /**
     * @inheritDoc
     */
    public function error(mixed $data): void
    {
        $this->output($data, 'error');
    }

    /**
     * @inheritDoc
     */
    public function output(string $data, string $type): void
    {
        $file = fopen(dirname(__DIR__, 5) . "/app.log", 'a+');
        $data = "[" . date('Y-m-d H:i:s') . "] <APP_ENV> " . strtoupper($type) . ": " . $data ."\n";
        fwrite($file, $data);
        fclose($file);
    }
}