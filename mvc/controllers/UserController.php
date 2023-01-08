<?php

namespace app\controllers;

use app\models\User;
use core\Controller;
use core\Request;
use core\Response;

class UserController extends Controller {

    public function getAllUsers(Request $request, Response $response) {
        $userList = User::find([]);
        $response->sendJson($userList);
    }

    public function userInfo(Request $request, Response $response) {

        if ($request->isGet()) {
            $account = $request->getBody()['account'] ?? null;
        }

        if (!$account) {
            return $response->sendJson([
                'success' => false,
                'message' => 'Данные об аккаунте не были переданы'
            ], 200);
        }

    }

}