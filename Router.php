<?php
namespace daviscodedev\phpmvc;

use daviscodedev\phpmvc\exception\Not_Found_Exception;

class Router {

    protected array $routes = [];
    public Request $request;
    public Response $response;

    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }


    public function get($path, $callback) {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve() {
        $path = $_SERVER['REQUEST_URI'];
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if($callback === false) {
            throw new Not_Found_Exception();
        }
        if(is_string($callback)) {
            return Application::$app->view->render_view($callback);
        }



        if(is_array($callback)) {
            /** @var \daviscodedev\phpmvc\Controller $controller */
            $controller = new $callback[0];
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;
            foreach($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
        }
        return call_user_func($callback, $this->request, $this->response);



    }

}