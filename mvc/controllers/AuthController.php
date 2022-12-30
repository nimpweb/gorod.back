<?php

namespace app\controllers;

use app\models\User;
use core\Application;
use core\Controller;
use core\Request;

class AuthController extends Controller {

    public function login () {
        $this->setLayout('auth');
        return $this->render('login');
    }

    public function register(Request $request) {
        $user = new User();
        if ($request->isPost()) {
            if ($user->validateRequest($request) && $user->save()) {
                
                Application::$app->response->redirect('/');
            }
            // \core\Helper::debug($request->getBody(), true);
        }
        return $this->render('register', [
            'model' => $user,
        ]);
    }

}
