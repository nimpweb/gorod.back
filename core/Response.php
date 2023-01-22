<?php

namespace core;

class Response {

    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const PAYMENT_REQUIRED = 402;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const REQUEST_TIMEOUT = 408;
    const INTERNAL_SERVER_ERROR = 500;

    public static function fromXmlToJson(string $xml) {
        $data = simplexml_load_file($xml);
        return json_decode(json_encode($data), true);
    }

    public function redirect(string $url) {
        header('Location: '. $url);
    }

    public function setStatusCode(int $code) {
        http_response_code($code);
    }

    public function json(array $data, int $code = self::OK, array $headers = ["Content-Type: application/json; charset=UTF-8"]) {
        if (!empty($headers)) {
            foreach ($headers as $header) header($header);
        }
        $this->setStatusCode($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);
        return;
    }

    public function jsonSuccess(mixed $data) {
        if (gettype($data) != 'array') { $data = json_decode(json_encode($data), true); }
        try {
            $this->json(array_merge(['success' => true], $data), self::OK);
        } catch (\Throwable $th) {
            $this->json(['success' => false, 'message' => $th->getMessage()], $th->getCode());
        }
        return ;
    }
    
    public function jsonFailure(string $message, int $code = self::NOT_FOUND, array $errors = []) {
        if (gettype($errors) != 'array') { $errors = json_decode(json_encode($errors), true); }
        return $this->json(array_merge(['success' => false, 'message' => $message], $errors), $code);
    }

    public function jsonFail(int $code = self::NOT_FOUND) {
        $message = "";
        switch ($code) {
            case self::NOT_FOUND:
                $message = "Page or data not Found";
                break;
            case self::FORBIDDEN:
                $message = "Access Denied";
                break;
            default:
                $message = "";
        }
        return $this->jsonFailure($message, $code);
    }

}