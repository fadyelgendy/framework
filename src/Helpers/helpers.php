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
    function abort(Throwable $exception, int $code = 422)
    {
        http_response_code(500);
        echo '<pre style="padding: 1rem; background: #d5d5d5">';
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        echo "<b style='color: red;'> Message: " . $exception->getMessage() . "</b><br>";
        echo "File: " . $exception->getFile() . "<br>";
        echo "Line: " . $exception->getLine() . "<br>";
        echo "Trace: " . $exception->getTraceAsString();

        echo '</pre>';
        die();
    }
}

/**
 * Dump and die
 */
if (!function_exists('dd')) {
    function dd($vars): void
    {
        echo '<pre style="padding: 1rem; background: #d5d5d5">';
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        print_r($vars);
        echo '</pre>';
        die();
    }
}

/**
 * render a view
 */
if (!function_exists('view')) {
    function view(string $view, array $data = [])
    {
        return \Lighter\Framework\Facades\View::render($view, $data);
    }
}

/**
 * App Instance
 */
if (!function_exists('app')) {
    function app(): \Lighter\Framework\Application
    {
        return \Lighter\Framework\Application::getInstance();
    }
}

/**
 * Config
 */
if (! function_exists('config')) {
    function config(string $path): null|string
    {
        $path = explode('.', $path);
        $file = $path[0] . ".php";

        if (!file_exists($file)) {
            return null;
        }

        $config = file_get_contents(dirname(__DIR__, 4) . "/config/" . $file);
        return $config[$path[1]];
    }
}