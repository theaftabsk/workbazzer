<?php
/**
 * Enterprise Routing System
 * Handles API and Web routes with middleware support.
 */

class Router {
    private static array $routes = [];
    private static array $middlewares = [];
    private static string $groupPrefix = '';
    private static array $groupMiddleware = [];

    public static function get(string $uri, $action) {
        self::addRoute('GET', $uri, $action);
        return new self;
    }

    public static function post(string $uri, $action) {
        self::addRoute('POST', $uri, $action);
        return new self;
    }

    public static function group(array $attributes, callable $callback) {
        $previousPrefix = self::$groupPrefix;
        $previousMiddleware = self::$groupMiddleware;

        self::$groupPrefix .= $attributes['prefix'] ?? '';
        if (isset($attributes['middleware'])) {
            self::$groupMiddleware = array_merge(self::$groupMiddleware, (array) $attributes['middleware']);
        }

        $callback();

        self::$groupPrefix = $previousPrefix;
        self::$groupMiddleware = $previousMiddleware;
    }

    private static function addRoute(string $method, string $uri, $action) {
        $uri = self::$groupPrefix . $uri;
        $uri = '/' . trim($uri, '/');
        
        self::$routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middleware' => self::$groupMiddleware
        ];
    }

    public function middleware($middleware) {
        $lastRouteIndex = count(self::$routes) - 1;
        if ($lastRouteIndex >= 0) {
            self::$routes[$lastRouteIndex]['middleware'] = array_merge(
                self::$routes[$lastRouteIndex]['middleware'],
                (array) $middleware
            );
        }
        return $this;
    }

    public static function dispatch() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        
        // Handle trailing slash (normalize)
        $requestUri = '/' . trim($requestUri, '/');

        foreach (self::$routes as $route) {
            if ($route['method'] === $requestMethod && $route['uri'] === $requestUri) {
                
                // Execute Middlewares
                foreach ($route['middleware'] as $middleware) {
                    self::executeMiddleware($middleware);
                }

                // Execute Action
                if (is_callable($route['action'])) {
                    return call_user_func($route['action']);
                } elseif (is_string($route['action'])) {
                    // For legacy support: if action is a file path
                    $file = __DIR__ . '/../' . ltrim($route['action'], '/');
                    if (file_exists($file)) {
                        require_once $file;
                        return;
                    }
                }
            }
        }

        // No route found
        Logger::error("Route not found: $requestMethod $requestUri");
        http_response_code(404);
        if (strpos($requestUri, '/api/') === 0) {
            Security::jsonError('API Endpoint Not Found', 404);
        } else {
            require __DIR__ . '/../errors/404.php';
        }
        exit;
    }

    private static function executeMiddleware(string $middleware) {
        switch ($middleware) {
            case 'auth':
                Auth::requireLogin();
                break;
            case 'guest':
                if (Auth::check()) {
                    redirect(Auth::dashboardUrl(Auth::role()));
                }
                break;
            case 'admin':
                Auth::requireRole('admin');
                break;
            case 'client':
                Auth::requireRole('client');
                break;
            case 'freelancer':
                Auth::requireRole('freelancer');
                break;
            case 'csrf':
                Security::verifyCsrf();
                break;
        }
    }
}
