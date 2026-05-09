<?php
/**
 * Enterprise Helpers
 * Global utility functions for easier development.
 */

if (!function_exists('env')) {
    function env(string $key, $default = null) {
        return $_ENV[$key] ?? (defined($key) ? constant($key) : $default);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url, int $statusCode = 302) {
        header("Location: $url", true, $statusCode);
        exit;
    }
}

if (!function_exists('response')) {
    function response(array $data, int $status = 200) {
        Security::jsonOut($data, $status);
    }
}

if (!function_exists('view')) {
    function view(string $path, array $data = []) {
        extract($data);
        $file = __DIR__ . '/../' . $path . '.php';
        if (file_exists($file)) {
            require $file;
        } else {
            Logger::error("View not found: $path");
            http_response_code(404);
            require __DIR__ . '/../errors/404.php';
        }
        exit;
    }
}

if (!function_exists('asset')) {
    function asset(string $path) {
        return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url(string $path) {
        return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('auth_user')) {
    function auth_user() {
        return Auth::user();
    }
}

if (!function_exists('time_ago')) {
    function time_ago($timestamp) {
        $time = is_numeric($timestamp) ? $timestamp : strtotime($timestamp);
        $diff = time() - $time;
        
        if ($diff < 1) return 'just now';
        
        $intervals = [
            31536000 => 'year',
            2592000  => 'month',
            604800   => 'week',
            86400    => 'day',
            3600     => 'hour',
            60       => 'minute',
            1        => 'second'
        ];
        
        foreach ($intervals as $secs => $label) {
            $div = $diff / $secs;
            if ($div >= 1) {
                $rounded = round($div);
                return $rounded . ' ' . $label . ($rounded > 1 ? 's' : '') . ' ago';
            }
        }
        return 'recently';
    }
}
