<?php
namespace App;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, string $handler): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $this->normalizePath($path),
            'handler' => $handler,
        ];
    }

    public function run(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $requestPath = $this->normalizePath($this->removeBasePath($uri));

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->match($route['path'], $requestPath);
            if ($params === null) {
                continue;
            }

            [$controllerName, $methodName] = explode('@', $route['handler'], 2);
            $controllerClass = '\\' . ltrim($controllerName, '\\');

            if (!class_exists($controllerClass) || !method_exists($controllerClass, $methodName)) {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Controlador o metodo no encontrado',
                ]);
                return;
            }

            $controller = new $controllerClass();
            call_user_func_array([$controller, $methodName], $params);
            return;
        }

        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Ruta no encontrada',
        ]);
    }

    private function match(string $routePath, string $requestPath): ?array
    {
        $routeParts = explode('/', trim($routePath, '/'));
        $requestParts = explode('/', trim($requestPath, '/'));

        if ($routeParts === ['']) {
            $routeParts = [];
        }

        if ($requestParts === ['']) {
            $requestParts = [];
        }

        if (count($routeParts) !== count($requestParts)) {
            return null;
        }

        $params = [];

        foreach ($routeParts as $index => $part) {
            if (preg_match('/^\{[a-zA-Z_][a-zA-Z0-9_]*\}$/', $part)) {
                $params[] = $requestParts[$index];
                continue;
            }

            if ($part !== $requestParts[$index]) {
                return null;
            }
        }

        return $params;
    }

    private function removeBasePath(string $uri): string
    {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');

        if ($basePath !== '' && $basePath !== '/' && strpos($uri, $basePath) === 0) {
            return substr($uri, strlen($basePath)) ?: '/';
        }

        return $uri;
    }

    private function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');
        return $path === '//' ? '/' : $path;
    }
}
