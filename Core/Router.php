<?php

namespace Core;

class Router
{
    const NAMESPACE = 'App\Controller\\';

    protected $routes = array();

    protected $params = array();

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param string $route
     * @param array $params
     */
    public function add(string $route, array $params = array())
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    /**
     * @param $url
     * @return bool
     */
    private function match(string $url)
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;

                return true;
            }
        }

        return false;
    }

    /**
     * @param $url
     * @throws \Exception
     */
    public function dispatch(string $url)
    {
        $url = $this->removeQueryStringVariables($url);

        if (!$this->match($url)) {
            throw new \Exception('No route matched.', 404);
        }

        $controller = $this->params['controller'];
        $controller = self::NAMESPACE . $controller;

        if (!class_exists($controller)) {
            throw new \Exception("Controller class $controller not found");
        }

        $controllerObject = new $controller($this->params);
        $action = $this->params['action'];

        if (!method_exists($controllerObject, $action)) {
            throw new \Exception("Method $action in controller $controller cannot be called");
        }

        $controllerObject->$action();

    }

    /**
     * @param $url
     * @return string
     */
    protected function removeQueryStringVariables(string $url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }

}