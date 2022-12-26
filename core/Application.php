<?php
namespace core;

class Application {

    public Router $router;
    public Request $request;
    public Response $response;
    public Controller $controller;
    
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
    
    public function __construct($rootPath) {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
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