<?php

namespace Framework;

use App\Controllers\ErrorController;

class Router
{
    protected $routes = [];

    public function registerRoute($method, $uri, $action)
    {
        list($controller, $controllerMethod) = explode('@', $action);
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod
        ];
    }

    /**
     * Add a GET route
     */
    public function get($uri, $controller)
    {
        $this->registerRoute('GET', $uri, $controller);
    }

    /**
     * Add a POST route
     */
    public function post($uri, $controller)
    {
        $this->registerRoute('POST', $uri, $controller);
    }

    /**
     * Add a PUT route
     */
    public function put($uri, $controller)
    {
        $this->registerRoute('PUT', $uri, $controller);
    }

    /**
     * Add a DELETE route
     */
    public function delete($uri, $controller)
    {
        $this->registerRoute('DELETE', $uri, $controller);
    }

    /**
     * Route the request
     * @param string $uri
     * @param string $method
     * @return void
     */
    public function route($uri, $method)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD']; 

        //Check for _method input 
        if ($requestMethod === 'POST' && isset($_POST
        ['_method'])) {
            //Override the request method with the value of _method
            $requestMethod = strtoupper($_POST['_method']);
        } 

        foreach ($this->routes as $route) {
        //Split the current URI into segments
            $uriSegments = explode('/', trim($uri, '/'));

        //Split the route
            $routeSegments = explode('/', trim($route['uri'], '/'));

            $match = true;

            if (count($uriSegments) === count($routeSegments) &&
             strtoupper($route['method']) === strtoupper($requestMethod)) {
                $params = [];

                $match = true;
                for($i = 0; $i < count($uriSegments); $i++) {
                    //If the uri do not match and there is no value between the {id}
                    if($routeSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeSegments[$i])) {  // ← fixed missing $
                        $match = false;
                        break;
                    } 
                    if(preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) { 
                        $params[$matches[1]] = $uriSegments[$i];             
                    }
                }
                if ($match) {
                    //Extract controller and controller method
                    $controller = 'App\\Controllers\\' . $route['controller'];
                    $controllerMethod = $route['controllerMethod'];

                    //Instantiate controller class
                    $controllerInstance = new $controller();
                    $controllerInstance->$controllerMethod ($params);
                    return;
                }
            }
        }

        ErrorController::notFound();
    }
}