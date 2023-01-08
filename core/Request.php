<?php
namespace core;

class Request {


    public function getPath() {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) return $path;
        return substr($path, 0, $position);
    }

    public function method() {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('Access-Control-Allow-Origin: http://localhost:3000');
            header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
            // header('Access-Control-Allow-Headers: token, Content-Type');
            header('Access-Control-Allow-Headers: *');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 1728000');
            header('Content-Length: 0');
            header('Content-Type: text/plain');
            die();
        }
        // if (isset($_SERVER['HTTP_ORIGIN'])) {
        //     header("Access-Control-Allow-Origin: *");
        //     header('Access-Control-Allow-Credentials: true');
        //     header('Access-Control-Max-Age: 86400');    
        // }
        // if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        //     if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        //     // may also be using PUT, PATCH, HEAD etc
        //     header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        
        //     if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        //         header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        //     exit(0);
        // }
        header('Access-Control-Allow-Origin: http://localhost:3000');
        header("Access-Control-Allow-Headers: *");
        return strtolower($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function isGet() {
        return $this->method() == 'get';
    }

    public function isPost() {
        return $this->method() == 'post';
    }

    public function getBody() {
        $body = [];
        if ($this->method() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->method() === 'post') {
            $post = (!empty($_POST)) ? $_POST : json_decode(file_get_contents('php://input'), true);
            foreach ($post as $key => $value) {
                $body[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }

    public function sendSoap(string $host, mixed $data, string $command, bool $translateToJsonIfSuccess = true) {
        $soapClient = new \SoapClient($host);
        $response = $soapClient->{$command}($this->transformToXml($data));
        if ($translateToJsonIfSuccess && $response) {
            $xml = simplexml_load_string($response);
            return json_decode(json_encode($xml));
        }
        return $response;
    }

    private function transformToXml(array $data) {
        return "
            <?xml version=\"1.0\" encoding=\"UTF-8\"?>
        ";
    }

}