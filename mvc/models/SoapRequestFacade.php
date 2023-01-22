<?php 

namespace app\models;

class SoapRequestFacade {

    private function init() {
        $soapHost = $_ENV['SOAP_HOST'] ?? null;
        if (!$soapHost) return null;
        return new \SoapClient($soapHost, [
            "trace" => 1,
            "exceptions" => true,
            "local_cert" => "sg.crt",
            "style" => SOAP_RPC,
            "use" => SOAP_ENCODED,
            "soap_version" => SOAP_1_1,
            "location" => "localhost"
        ]);
    
    }

    public static function get(string $action, ...$params) {
        $client = self::init();
        $response = $client->{$action}(...$params);
        if ($response && strlen(trim($response)) > 0) {
            $xmlContent = simplexml_load_file($response);
            return json_decode(json_encode($xmlContent), true);
        }
        return [];
    }

    public static function getServiceList(...$args) {
        $response = self::get("ReqServiceList", ...$args);
        if ($response && !empty($response) && is_array($response)) {
            return [];
        }
    }


    public static function getAbonentByAccountAndService(string $account, string $serviceNumber) {
        
        return [
            'AbonentFilter' => [
                'AbonentSearch' => [
                    'MFilter' => [
                        'account' => $account
                    ],
                    'srvnum' => $serviceNumber,
                    'exactsearch' => '0'
                ]
            ]
        ];
    }

}