<?php

namespace core;

class Response {

    public static int $OK = 200;
    public static int $Created = 201;
    public static int $BadRequest = 400;
    public static int $Unauthorized = 401;
    public static int $PaymentRequired = 402;
    public static int $Forbidden = 403;
    public static int $NotFound = 404;
    public static int $MethodNotAllowed = 405;
    public static int $InternalServerError = 500;

    public function redirect(string $url) {
        header('Location: '. $url);
    }

    public function setStatusCode(int $code) {
        http_response_code($code);
    }

    public function sendJson(mixed $data, int $code = 200) {
        // header("Access-Control-Allow-Origin: *");
        // header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        // header("Access-Control-Allow-Headers: X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        $this->setStatusCode($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);
        return;
    }

}