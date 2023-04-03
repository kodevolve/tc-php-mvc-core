<?php
namespace daviscodedev\phpmvc;

use daviscodedev\phpmvc\db\Database;
use daviscodedev\phpmvc\db\DB_Model;

#[\AllowDynamicProperties]
class Application {

    public static string $ROOT_DIR;
    public static Application $app;

    public string $layout = 'main';
    public string $user_class;
    public Router $router;
    public Request $request;
    public Response $response;
    public ?Controller $controller = null;
    public Session $session;
    public Database $db;
    public ?User_Model $user;
    public View $view;

    public function getController(): Controller { return $this->controller; }
    public function setController(Controller $controller): self { $this->controller = $controller; return $this; }

    public function __construct($root_dir, array $config) {
        $this->user_class = $config['user_class'];
        self::$ROOT_DIR = $root_dir;
        self::$app = $this;
        // $this->controller = new Controller;
        $this->response = new Response();
        $this->request = new Request();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View;

        $this->db = new Database($config['db']);

        $primary_value = $this->session->get('user');
        if($primary_value) {
            $user_object = new $this->user_class;
            $primary_key = $user_object->primary_key();
            $this->user = $user_object->find_one([$primary_key => $primary_value]);
        } else {
            $this->user = null;
        }
    }




    public function run() {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->set_status_code($e->getCode());
            echo $this->view->render_view('_error', [
                'exception' => $e,
            ]);
        }
    }

    public function login(User_Model $user) {
        $this->user = $user;
        $primary_key = $user->primary_key();
        $primary_value = $user->{$primary_key};
        $this->session->set('user',$primary_value);
        return true;
    }

    public function logout() {
        $this->user = null;
        $this->session->remove('user');
    }
    public static function is_guest() {
        return !self::$app->user;
    }


}