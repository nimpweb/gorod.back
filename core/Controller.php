<?php

namespace core;

use core\middlewares\BaseMiddleware;

class Controller {

    public string $layout = "default";
    public string $action = '';
    /**
     * @var \core\middleware\BaseMiddleware[]
     */
    protected array $middlewares = [];

    public function registerMiddleware(BaseMiddleware $middleware) {
        $this->middlewares[] = $middleware;
    }

    public function setLayout($layout) {
        $this->layout = $layout;
    }

    public function render($view, $params = []) {
        return Application::$app->router->renderView($view, $params);
    }

    public function getMiddlewares() {
        return $this->middlewares;
    }
    
}