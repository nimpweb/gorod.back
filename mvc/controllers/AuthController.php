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
            $loginResponse = $loginForm->login();
            if ($loginResponse && $loginResponse['success']) {
                return $response->jsonSuccess($loginResponse);
            }
            return $response->jsonFailure('Вы не прошли авторизацию! Проверьте правильность ввода данных', Response::UNAUTHORIZED, $loginForm->getValidatedErrorMessages());
        }
        return $response->json(['success' => false], Response::NOT_FOUND);
    }

    public function register(Request $request, Response $response) {
        if (!$request->isPost()) { 
            return $response->jsonFailure('', Response::METHOD_NOT_ALLOWED);
         }
         $userArray = User::create($request);
         if ($userArray['success']) {
            $user =  $userArray['user'];
            unset($user['password']);
            return $response->jsonSuccess(['user' => $user]);
         }
        return $response->jsonFailure('Что-то пошло не так...', Response::BAD_REQUEST, $userArray['errors']);
    }

    public function logout(Request $request, Response $response) {
        Application::$app->logout();
        $response->redirect('/');
    }
}
