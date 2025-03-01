<?php

class Router {
    private $routes = [];

    public function get($route, $controllerAction) {
        $this->routes['GET'][$route] = $controllerAction;
    }
    
    public function post($route, $controllerAction) {
        $this->routes['POST'][$route] = $controllerAction;
    }

    public function run() {
        $requestUri = explode('?', $_SERVER['REQUEST_URI'])[0]; // Ambil URL tanpa query string
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Cek apakah metode GET atau POST sudah terdaftar
        if (!isset($this->routes[$requestMethod])) {
            http_response_code(405);
            echo "405 Method Not Allowed";
            return;
        }

        // Cek apakah ada route yang cocok
        foreach ($this->routes[$requestMethod] as $route => $controllerAction) {
            $pattern = preg_replace('/\{(.+?)\}/', '([^/]+)', $route);
            if (preg_match("#^$pattern$#", $requestUri, $matches)) {
                array_shift($matches);
                $this->callController($controllerAction, $matches);
                return;
            }
        }

        http_response_code(404);
        header("Location: /");
    }

    private function callController($controllerAction, $params) {
        list($controllerName, $methodName) = explode('@', $controllerAction);
        
        $controllerFile = "../app/Controllers/$controllerName.php";
        if (!file_exists($controllerFile)) {
            http_response_code(500);
            echo "500 Internal Server Error - Controller tidak ditemukan";
            return;
        }

        require_once $controllerFile;
        if (!class_exists($controllerName)) {
            http_response_code(500);
            echo "500 Internal Server Error - Kelas Controller tidak ditemukan";
            return;
        }

        $controller = new $controllerName();
        if (!method_exists($controller, $methodName)) {
            http_response_code(500);
            echo "500 Internal Server Error - Method Controller tidak ditemukan";
            return;
        }

        call_user_func_array([$controller, $methodName], $params);
    }
}
