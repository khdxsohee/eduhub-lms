<?php
// app/core/Router.php (This is conceptual for a standalone router)

// This class is not directly used in the current App.php structure,
// as App.php handles the basic URL parsing and controller/method calling.
// A more advanced routing system might use this pattern.

class Router {
    protected $routes = [];

    public function get($uri, $callback) {
        $this->addRoute('GET', $uri, $callback);
    }

    public function post($uri, $callback) {
        $this->addRoute('POST', $uri, $callback);
    }

    protected function addRoute($method, $uri, $callback) {
        $this->routes[$method][$uri] = $callback;
    }

    public function dispatch($uri, $method) {
        if (array_key_exists($uri, $this->routes[$method])) {
            $callback = $this->routes[$method][$uri];
            if (is_callable($callback)) {
                call_user_func($callback);
            } elseif (is_string($callback)) {
                list($controller, $method) = explode('@', $callback);
                $controller = new $controller();
                call_user_func([$controller, $method]);
            }
        } else {
            // Handle 404
            echo "404 Not Found";
        }
    }
}