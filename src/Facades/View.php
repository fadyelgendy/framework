<?php

namespace Lighter\Framework\Facades;

class View
{
    protected  \Lighter\Framework\View\View $view;

    public function __construct()
    {
        $this->view = \Lighter\Framework\View\View::getInstance();
    }

    public static function render(string $view, array $data = [])
    {
        return (new static())->view->render($view, $data);
    }
}