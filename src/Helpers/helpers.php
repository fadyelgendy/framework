<?php

/**
 * Asset path, css, js
 */
if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $assets_path = DIRECTORY_SEPARATOR . "assets";

        return $assets_path . DIRECTORY_SEPARATOR . $path;
    }
}

/**
 * Public Path
 */
if (!function_exists('public_path')) {
    function public_path(): string
    {
        return "public";
    }
}
/**
 * Views path
 */
if (!function_exists('views_path')) {
    function views_path(): string
    {
        return dirname(__FILE__) . "/views/";
    }
}

/**
 * csrf token
 */
if (!function_exists('csrf_token')) {
    function csrf_token(): void
    {
        echo "<input type='hidden' name='_token' value='" . $_SESSION['_token'] . "'>";
    }
}

if (!function_exists('error')) {
    function error(string $key): null|string
    {
        return $_SESSION[$key] ?? null;
    }
}

/**
 * Abort
 */
if (!function_exists('abort')) {
    function abort(string $key, string $message, int $code = 422)
    {
        http_response_code($code);
        $_SESSION[$key] = $message;
        header("Location: /");
        exit();
    }
}

/**
 * Dump and die
 */
if (!function_exists('dd')) {
    function dd($vars): void
    {
        echo '<pre>';
        var_dump($vars);
        echo '</pre>';
        die();
    }
}