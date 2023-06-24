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

        $file = is_null(config('app.view_path')) ? realpath(dirname(__DIR__, 5)) : config('app.view_path') ;

        ob_start();
        include($file .DIRECTORY_SEPARATOR . $view.".view.php");
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }
}