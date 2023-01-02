<?php

namespace app\controllers;

use app\models\User;
use core\Application;
use core\Controller;
use core\Helper;
use core\Request;

class AuthController extends Controller {

    public function login () {
        $this->setLayout('auth');
        return $this->render('login');
    }

    public function register(Request $request) {
        $user = new User();
        if ($request->isPost()) {

            // Helper::debug($user->validateRequest($request), true);
            if ($user->validateRequest($request) && $user->save()) {
                Application::$app->session->setFlash('sucess', 'Спасибо за регистрацию!');
                Application::$app->response->redirect('/');
            }
            \core\Helper::debug(Application::$app->model->errors, true);
        }
        return $this->render('register', [
            'model' => $user,
        ]);
    }

}
