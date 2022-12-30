<?php

namespace core;

class Response {

    public function redirect(string $url) {
        header('Location: '. $url);
    }

    public function setStatusCode(int $code) {
        http_response_code($code);
    }

}