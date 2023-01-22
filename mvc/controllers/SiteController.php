<?php

namespace app\controllers;

use core\Controller;
use core\Request;

class SiteController extends Controller {

    public function home() {

        $salt = '123';
        $password = '12345678';
        $hash = md5(md5($password) + $salt);

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