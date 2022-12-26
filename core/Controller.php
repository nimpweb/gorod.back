<?php

namespace core;

class Controller {

    public string $layout = "";

    public function render($view, $params = []) {
        return Application::$app->router->renderView($view, $params);
    }
    
}