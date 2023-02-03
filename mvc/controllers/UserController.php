<?php

namespace app\controllers;

use app\models\SoapRequestFacade;
use app\models\User;
use core\Controller;
use core\Helper;
use core\middlewares\AuthMiddleware;
use core\Request;
use core\Response;
use core\Token;
use Exception;

class UserController extends Controller {

    public function __construct() {
        // add restricted routes by authenticating
        $this->registerMiddleware(new AuthMiddleware(['userInfo']));
    }

    public function getAllUsers(Request $request, Response $response) {
        $userList = User::find([]);
        $response->json($userList);
    }

    public function profile(Request $request, Response $response) {
        $token = $request->getValidToken();
        if (!$token || !$token['success']) return $response->jsonFail(Response::FORBIDDEN);
        if ($request->isGet()) {
            $user = User::byId($token['user']->userid, 'userid');
            $user->prepareInstance(["password", "passwordConfirm", "errors"]);
            return $response->jsonSuccess([
                'user' => $user,
                'services' => $user->getServices()
            ]);
        }
        return $response->jsonFail(Response::NOT_FOUND);
    }

    public function update(Request $request, Response $response) {
        if (!$request->isPost) throw new Exception("Method not allowed", Response::METHOD_NOT_ALLOWED);
        $candidate = new User();
        // $cadidate->load
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