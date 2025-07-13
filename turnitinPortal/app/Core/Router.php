<?php

namespace App\Core;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get($uri, $action)
    {
        $uri = trim($uri, '/');
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action)
    {
        $uri = trim($uri, '/');
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch($uri)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = trim($uri, '/');

        // Nếu route đã đăng ký với method cụ thể
        if (isset($this->routes[$method][$uri])) {
            return $this->runAction($this->routes[$method][$uri]);
        }

        // Hỗ trợ /status/{id}
        if (preg_match('#^status/(\d+)$#', $uri, $matches)) {
            return $this->runAction('StatusController@show', [$matches[1]]);
        }


        // Fallback tự động: controller/action/params
        $segments = explode('/', $uri);
        $controllerName = ucfirst(array_shift($segments) ?: 'home') . 'Controller';
        $actionName = array_shift($segments) ?: 'index';
        $params = $segments;

        return $this->runController($controllerName, $actionName, $params);
    }

    protected function runAction($action, $params = [])
    {
        list($controllerName, $method) = explode('@', $action);
        $controllerClass = 'App\\Controllers\\' . $controllerName;

        if (!class_exists($controllerClass)) {
            http_response_code(404);
            echo "Controller '$controllerClass' không tồn tại.";
            exit;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            http_response_code(404);
            echo "Action '$method' không tồn tại trong $controllerClass.";
            exit;
        }

        return call_user_func_array([$controller, $method], $params);
    }

    protected function runController($controllerName, $actionName, $params = [])
    {
        $controllerClass = 'App\\Controllers\\' . $controllerName;

        if (!class_exists($controllerClass)) {
            http_response_code(404);
            echo "Controller '$controllerClass' không tồn tại.";
            exit;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $actionName)) {
            http_response_code(404);
            echo "Action '$actionName' không tồn tại trong $controllerClass.";
            exit;
        }

        return call_user_func_array([$controller, $actionName], $params);
    }
}
