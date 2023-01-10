<?php 

namespace app\models;

class SoapRequestFacade {

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