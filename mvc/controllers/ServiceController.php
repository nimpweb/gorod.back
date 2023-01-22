<?php

namespace app\controllers;

use app\models\Service;
use core\Controller;
use core\Request;
use core\Response;

class ServiceController extends Controller {

    public function list(Request $request, Response $response) {
        $result = Service::list();
        $response->jsonSuccess($result);
    }

}