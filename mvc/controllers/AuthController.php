<?php

namespace app\controllers;

use app\models\RegisterModel;
use core\Controller;
use core\Request;

class AuthController extends Controller {

    public function login () {
        $this->setLayout('auth');
        return $this->render('login');
    }

    public function register(Request $request) {
        $registerModel = new RegisterModel();
        if ($request->isPost()) {
            // \core\Helper::debug($request->getBody(), true);
        }
        return $this->render('register', [
            'model' => $registerModel,
        ]);
    }

}
