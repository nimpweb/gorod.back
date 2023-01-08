<?php

namespace app\controllers;

use app\models\User;
use app\models\LoginForm;
use core\Application;
use core\Controller;
use core\Helper;
use core\Request;
use core\Response;

class AuthController extends Controller {

    public function login (Request $request, Response $response) {
        $loginForm = new LoginForm();
        if ($request->isPost()) {
            if ($loginForm->validateRequest($request) && $loginForm->login()) {
                $response->redirect('/');
                return;
            }
        }
        return $this->render('login', [
            'model' => $loginForm
        ]);
    }

    public function register(Request $request, Response $response) {
        if (!$request->isPost()) { 
            return $response->sendJson([], Response::$MethodNotAllowed);
         }
         $user = new User();
         if ($user->validateRequest($request) && $user->insert()) {
            return $response->sendJson(['success' => true, 'message' => "Some text was sent"]);
            unset($user['password']);
        }
        return $response->sendJson($user);

        // return $this->render('register', [
        //     'model' => $user,
        // ]);
    }

    public function logout(Request $request, Response $response) {
        Application::$app->logout();
        $response->redirect('/');
    }
}
