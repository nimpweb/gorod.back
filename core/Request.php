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
        return strtolower($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function getAuthenticatedToken() {
        $allHeaders = getallheaders();
        if ($allHeaders) {
            $token = $allHeaders['Authorization'] ?? false;
            if ($token) {
                $token = $token ? substr($token, strlen('Bearer '), strlen($token)) : null;
            }
            return $token;
        }
        return false;
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
            $raw = (!empty($_POST)) ? $_POST : json_decode(file_get_contents('php://input'), true);
        }
        if ($this->method() === 'post') {
            $raw = (!empty($_POST)) ? $_POST : json_decode(file_get_contents('php://input'), true);
        }
        if (!empty($raw)) { 
            foreach ($raw as $key => $value) {
                $body[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }

    public function sendSoap(string $action, string $command, array $data, bool $translateToJsonIfSuccess = true) {
        $config = Application::$app->getConfig('soap');
        if ($config === false) return;
        $host = $config['SOAP_HOST'] ?? false;
        $terminal = $config["TERMINAL"] ?? false;
        if (!$host || !$terminal) return;

        return $this->transformToXml($action, $command, $data, $terminal);
        $soapClient = new \SoapClient($host);
        $response = $soapClient->{$command}($this->transformToXml($action, $command, $data, $terminal));
        if ($translateToJsonIfSuccess && $response) {
            $xml = simplexml_load_string($response);
            return json_decode(json_encode($xml));
        }
        return $response;
    }

    public function arrayToXmlMarkup(array $array) {
        $xml = "";
        foreach($array as $key => $value) {
            $xml .= "<$key>";
            if (is_array($value)) {
                $xml .= $this->arrayToXmlMarkup($value);
            } else {
                $xml .= $value; 
            }
            $xml .= "</$key>";
        }
        return $xml;
    }

    private function transformToXml(string $action, string $command, array $data, string $terminal) {

        $xml = $this->arrayToXmlMarkup($data);

        return "
            <?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <Document>
                <GIN>
                    <$action>
                        <$command>
                            $xml
                        </$command>
                    </$action>
                    <META-INF>
                        <ENTRY>
                            <name>TERMINAL</name>
                            <value>$terminal</value>
                        </ENTRY>
                    </META-INF>
                </GIN>
            </Document>
            ";
    }

}