<?php
namespace core;

class Request {

    public static function arrayToXmlMarkup(array $array, $xmlHeader = false) {
        $xml = "";
        foreach($array as $key => $value) {
            $xml .= "<$key>";
            if (is_array($value)) {
                $xml .= self::arrayToXmlMarkup($value);
            } else {
                $xml .= $value; 
            }
            $xml .= "</$key>";
        }
        if ($xmlHeader) $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" .$xml; 
        return $xml;
    }

    public static function sendCurl(string $url, mixed $data, array $headers = [], array $certs = [], string $method = 'POST', bool $transformToJson = true) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        if ($response) {

        }
    }


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
            header('Access-Control-Allow-Credentials: false');
            header('Access-Control-Max-Age: 1728000');
            header('Content-Length: 0');
            header('Content-Type: text/plain');
            die();
        }
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *'); 
        header('Access-Control-Allow-Credentials: false');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Max-Age: 1728000');

        return strtolower($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function getValidToken() : array | bool {
        $token = $this->getAuthenticatedToken();
        if ($token) {
            $decoded = Token::check($token);
            if ($decoded) return $decoded;
        }
        return false;
    }

    public function getAuthenticatedToken() {
        $allHeaders = getallheaders();
        if (!empty($allHeaders)) {
            $token = $allHeaders['Authorization'] ?? '';
            if ($token) {
                $token = $token ? substr($token, strlen('Bearer '), strlen($token)) : null;
                return $token;
            }
        }
        return '';
    }

    public function isGet() {
        return $this->method() == 'get';
    }

    public function isPost() {
        return $this->method() == 'post';
    }

    public function __get(string $name): mixed {
        $body = $this->getBody();
        if (!empty($body) && array_key_exists($name, $body)) {
            return $body[$name];
        }
        return null;
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

    private function transformToXml(string $action, string $command, array $data, string $terminal) {

        $xml = self::arrayToXmlMarkup($data);

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