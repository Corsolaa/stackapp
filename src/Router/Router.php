<?php

namespace StackSite\Router;

class Router
{
    private array $routes = [];

    public function add(string $path, Route $route): void
    {
        $this->routes[$path] = $route;
    }

    public function dispatch(string $requestedPath): void
    {
        if (isset($this->routes[$requestedPath])) {
            $this->routes[$requestedPath]->handle();
        } else {
            $this->handleNotFound();
        }
    }

    private function handleNotFound(): void
    {
        http_response_code(404);
        echo "404 Not Found";
    }
}