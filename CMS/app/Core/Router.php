<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, array $handler, array $roles = []): void
    {
        $this->add('GET', $path, $handler, $roles);
    }

    public function post(string $path, array $handler, array $roles = []): void
    {
        $this->add('POST', $path, $handler, $roles);
    }

    private function add(string $method, string $path, array $handler, array $roles): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => rtrim($path, '/') ?: '/',
            'handler' => $handler,
            'roles' => $roles,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $base = App::config('base_path', '');
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base)) ?: '/';
        }
        $path = rtrim($path, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }

            $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $route['path']);
            $regex = '#^' . $pattern . '$#';

            if (!preg_match($regex, $path, $matches)) {
                continue;
            }

            if ($method === 'POST' && !Csrf::verify($_POST['_csrf'] ?? '')) {
                Flash::set('error', 'Invalid request token. Please retry.');
                Response::redirect($path);
            }

            if (!empty($route['roles'])) {
                if (!Auth::check()) {
                    Response::redirect('/login');
                }
                if (!in_array(Auth::user()['role'], $route['roles'], true)) {
                    http_response_code(403);
                    echo '403 Forbidden';
                    return;
                }
            }

            $params = [];
            foreach ($matches as $key => $value) {
                if (!is_int($key)) {
                    $params[$key] = $value;
                }
            }

            [$class, $action] = $route['handler'];
            (new $class())->$action($params);
            return;
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}
