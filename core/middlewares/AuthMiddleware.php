<?php

namespace core\middlewares;

use core\Application;
use core\exceptions\ForbiddenException;
use core\Response;
use core\Token;

class AuthMiddleware extends BaseMiddleware {

    public array $actions = [];

    public function __construct(array $actions = []) {
        $this->actions = $actions;
    }

    public function execute() {
        $currentAction = Application::$app->controller->action;
        $token = Application::$app->request->getAuthenticatedToken();
        $isAuthenticated = Token::isValid($token);
        if (!$isAuthenticated) {
            if (empty($this->actions) || in_array($currentAction, $this->actions)) {
                Application::$app->response->jsonFail(Response::FORBIDDEN);
                die;
            }
        }

    }

}