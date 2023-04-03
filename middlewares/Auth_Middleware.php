<?php
namespace app\core\middlewares;

use app\core\Application;
use app\core\exception\Forbidden_Exception;

class Auth_Middleware extends Base_Middleware {

    public array $actions = [];

    public function __construct(array $actions = []) {
        $this->actions = $actions;
    }

    public function execute() {
        if(Application::is_guest()) {
            if(empty($this->actions) || in_array(Application::$app->controller->action,$this->actions)) {
                throw new Forbidden_Exception();
            }

        }
    }
}