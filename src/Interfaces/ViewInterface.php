<?php

namespace Lighter\Framework\Interfaces;

use Lighter\Framework\View\View;

interface ViewInterface
{
    /**
     * Get View Instance
     * @return View
     */

    public static function getInstance(): View;
    /**
     * Render View File
     *
     * @param string $view
     * @return mixed
     */
    public function render(string $view, array $data = []): mixed;
}