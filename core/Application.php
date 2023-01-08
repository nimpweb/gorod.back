<?php
namespace core;

class Application {

    public string $userClass = '';
    public Router $router;
    public Request $request;
    public Response $response;
    public Controller $controller;
    public Database $db;
    public Session $session;
    public ?DbModel $user;
    
    public static string $ROOT_DIR;
    public static Application $app;

    public static function layoutPath() {
        return realpath(self::$ROOT_DIR . '/mvc/layout/');
    }

    public static function modelPath() {
        return realpath(self::$ROOT_DIR.'/mvc/models/');
    }

    public static function viewPath() {
        return realpath(self::$ROOT_DIR.'/mvc/views/');
    }

    public static function controllerPath() {
        return realpath(self::$ROOT_DIR.'/mvc/controllers/');
    }
    
    public function __construct($rootPath, array $config) {
        $this->userClass = $config['userClass'];
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
        $this->session = new Session();
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        
        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }
    }

    public function login(DbModel $user) {
        $this->user = $user;
        $this->session->set('user', $user->{$user->primaryKey()});
        return true;
    }

    public function logout() {
        $this->user = null;
        $this->session->remove('user');
        // $this->response->redirect('/');
    }

    public function run(): void {
        echo $this->router->resolve();
    }

    public function getController(): Controller {
        return $this->controller;
    }

    public function setController($controller): void {
        $this->controller = $controller;
    }

}