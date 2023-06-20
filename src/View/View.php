<?php

namespace Lighter\Framework\View;

class View implements \Lighter\Framework\Interfaces\ViewInterface
{
    protected static ?View $instance = null;

    public static function getInstance(): View
    {
        if (is_null((static::$instance))) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @inheritDoc
     */
    public function render(string $view, array $data = []): string|bool
    {
        extract($data);

        ob_start();
        include(realpath(dirname(__DIR__, 2)) . "/views/{$view}.view.php");
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }
}