<?php

namespace core;

class Response {

    const OK = 200;
    const CREATED = 201;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const PAYMENT_REQUIRED = 402;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const INTERNAL_SERVER_ERROR = 500;

    public function redirect(string $url) {
        header('Location: '. $url);
    }

    public function setStatusCode(int $code) {
        http_response_code($code);
    }

    public function json(mixed $data, int $code = self::OK, array $headers = ["Content-Type: application/json; charset=UTF-8"]) {
        if (!empty($headers)) {
            foreach ($headers as $header) header($header);
        }
        $this->setStatusCode($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);
        return;
    }

    public function jsonSuccess(mixed $data) {
        return $this->json(array_merge(['success' => true], $data), self::OK);
    }
    
    public function jsonFailure(string $message, int $code = self::NOT_FOUND, array $errors = []) {
        return $this->json(array_merge(['success' => false, 'message' => $message], $errors), $code);
    }

}