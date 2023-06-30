<?php

use JetBrains\PhpStorm\NoReturn;

const HTTP_OK = 200;
const HTTP_SERVER_ERROR = 500;
const HTTP_VALIDATION_ERROR = 422;

/**
 * Asset path, css, js
 */
if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return public_path() . "assets" . DIRECTORY_SEPARATOR . $path;
    }
}

/**
 * Public Path
 */
if (!function_exists('public_path')) {
    function public_path(): string
    {
        return (($_SERVER['REQUEST_SCHEME'] == 'http') ? "http" : "https") . "://" . $_SERVER['HTTP_HOST'] . "/";
    }
}
/**
 * Views path
 */
if (!function_exists('views_path')) {
    function views_path(): string
    {
        return dirname(__DIR__, 5) . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR;
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
        return $_SESSION["error_$key"] ?? null;
    }
}

/**
 * Flash Messages
 */
if (!function_exists('flash')) {
    function flash(array $data): void
    {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }
}

/**
 * Flash errors
 */
if (!function_exists('flash_errors')) {
    function flash_errors(array $data): void
    {
        foreach ($data as $key => $value) {
            $_SESSION["error_$key"] = $value;
        }
    }
}

/**
 * Old input
 */
if (!function_exists('old')) {
    function old(string $key): string {
        return "";
    }
}

/**
 * Redirect to a given Route
 */
if (!function_exists('redirect')) {
    /**
     * @throws Exception
     */
    function redirect(string $route, $status_code = HTTP_OK)
    {
        unset($_POST);
        http_response_code($status_code);
        app()->router()->redirect($route);
        header("Location: $route");
        exit();
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
        echo "<b style='background:#000; color:#FFF; padding: 0.3rem'>";
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        echo "</b> \n";
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
if (!function_exists('config')) {
    function config(string $path): null|string
    {
        $path = explode('.', $path);
        $file = dirname(__DIR__, 5) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . $path[0] . ".php";

        if (!file_exists($file)) {
            return null;
        }

        $config = require $file;
        return $config[$path[1]];
    }
}

/**
* Translation
*/
if (! function_exists('trans')) {
    function trans(string $key) {
        $locale = config('app.app_locale');
        $translation_file = dirname(__DIR__, 5) . DIRECTORY_SEPARATOR .'languages'. DIRECTORY_SEPARATOR. $locale. '.json';
        $contents = json_decode(file_get_contents($translation_file), true);
        echo $contents[$key] ?? $key;
    }
}