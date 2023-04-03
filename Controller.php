<?php
namespace app\core;

use app\core\middlewares\Base_Middleware;

class Controller {

    public string $layout = 'main';
    public string $action = '';

    /*
    ** @var \app\core\middlewares\Base_Middleware[]
    */
    protected array $middlewares = [];

    public function render($view, $params = []) {
        return Application::$app->view->render_view($view, $params);
    }
    public function set_layout($layout) {
        $this->layout = $layout;
    }

    public function register_middleware(Base_Middleware $middleware) {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array {
        return $this->middlewares;
    }

}