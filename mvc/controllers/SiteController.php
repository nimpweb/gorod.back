<?php

namespace app\controllers;

use core\Controller;
use core\Request;

class SiteController extends Controller {

    public function home() {
        $params = [
            "param1" => "yeah"
        ];
        return $this->render('home', $params);
    }

    public function contact() {
        return $this->render('contact');
    }

    public function handleContact(Request $request) {
        $body = $request->getBody();
        return "handleContact";
    }

}