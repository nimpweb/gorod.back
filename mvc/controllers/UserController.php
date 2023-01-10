<?php

namespace app\controllers;

use app\models\SoapRequestFacade;
use app\models\User;
use core\Controller;
use core\Request;
use core\Response;

class UserController extends Controller {

    public function getAllUsers(Request $request, Response $response) {
        $userList = User::find([]);
        $response->json($userList);
    }

    public function userInfo(Request $request, Response $response) {

        if ($request->isGet()) {
            $account = $request->getBody()['account'] ?? null;
            $srvnum = $request->getBody()['srvnum'] ?? null;
            if (!$account || !$srvnum) {
                return $response->json([
                    'success' => false,
                    'message' => 'Данные об аккаунте не были переданы',
                    ['account' => $account, 'srvnum' => $srvnum]
                ]);
            }
            $xml = $request->sendSoap('FORM', 'ReqAbonentList', SoapRequestFacade::getAbonentByAccountAndService($account, $srvnum));
            $response->json(['success'=> true, 'result' => $xml], 200, ['Content-type: application/xml']);
        }

    }

}