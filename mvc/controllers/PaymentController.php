<?php

namespace app\controllers;

use core\Controller;
use core\Request;
use core\Response;

class PaymentController extends Controller {

    private string $paymentHost = "";
    private string $merchant = "";
    private int $currencyType = 643;

    private string $approveUrl = "";
    private string $cancelUrl = "";
    private string $declineUrl = "";

    private function initConfig() {
        $this->paymentHost = $_ENV['PAYMENT_HOST'] ?? null;
        $this->merchant = $_ENV['PAYMENT_MERCHANT'] ?? null;
        $this->currencyType = $_ENV['PAYMENT_CURRENCY'] ?? null;

        $this->approveUrl = 'http://localhost:3000/payment-success';
        $this->cancelUrl = 'https://localhost:3000/payment-cancel';
        $this->declineUrl = 'https://localhost:3000/payment-decline';
    }

    public function __construct() {
        $this->initConfig();        
    }

    public function create(Request $request, Response $response) {
        if (!$this->paymentHost || !$this->merchant || !$this->currencyType) {
            return $response->jsonFailure("No data to connect a payment host", Response::BAD_REQUEST);
        }

        $orderNumber = 1;
        $amount = $request->amount ?? null;
        $description = "Квитанция №".$orderNumber;

        $xml = Request::arrayToXmlMarkup([
            'TKKPG' => [
                'Request' => [
                    'Operation' => 'CreateOrder',
                    'Language' => 'RU',
                    'Order' => [
                        'Merchant' => $merchant,
                        'Amount' => $amount,
                        'Currency' => $currency,
                        'Description' => $description,
                        'ApproveURL' => $approveUrl,
                        'CancelURL' => $cancelUrl,
                        'DeclineURL' => $declineUrl,
                    ]
                ]
            ]
        ], true);
        $orderCreationResult = Request::sendCurl($this->paymentHost, $xml, [], []);
        if ($orderCreationResult && !empty($orderCreationResult)) {
            $paymentUrl = $orderCreationResult['TKKPG']['Response']['Order']['URL'] ?? null;
            $paymentOrderId = $orderCreationResult['TKKPG']['Response']['Order']['OrderID'] ?? null;
            $paymentSessionId = $orderCreationResult['TKKPG']['Response']['Order']['SessionID'] ?? null;
            if (!$paymentUrl || !$paymentOrderId || !$paymentSessionId) {
                return $response->jsonFailure("Ошибка на платежном шлюзе! Попробуйте позже!", Response::BAD_REQUEST);
            }
            $paymentUrl = $paymentUrl."?ORDERID=$paymentOrderId&SESSIONID=$paymentSessionId";
            $response->jsonSuccess([
                'url' => $paymentUrl,
                'orderId' => $paymentOrderId,
                'sessionId' => $paymentSessionId,
                'amount' => $amount,
                'currency' => $this->currencyType,
                'description' => $description,
            ]);
        }
        return $response->jsonFailure("Нет данных", Response::NOT_FOUND);
    }

    public function status(Request $request, Response $response) {
        $paymentSessionId = $request->paymentSessionId ?? null;
        $paymentOrderId = $request->paymentOrderId ?? null;
        if (!$paymentSessionId || !$paymentOrderId) {
            return $response->jsonFailure("Нет идентификаторов запроса данных", Response::BAD_REQUEST);
        }
        $xml = Request::arrayToXmlMarkup([
            'TKKPG' => [
                'Request' => [
                    'Operation' => 'GetOrderStatus',
                    'Language' => 'RU',
                    'SessionID' => $paymentSessionId,
                    'Order' => [
                        'Merchant' => $this->merchant,
                        'OrderID' => $paymentOrderId
                    ]
                ]
            ]
        ], true);
        $result = Request::sendCurl($this->paymentHost, $xml, [], []);
        if ($result) {
            $orderStatusCode = $result['TKKPG']['Response']['Status'] ?? null;
            $orderStatusMessage = $result['TKKPG']['Response']['Order']['OrderStatus'] ?? null;
            if (!$orderStatusCode || !$orderStatusMessage) {
                $response->jsonFailure("Nodata", Response::BAD_REQUEST);
            }
        }

    }

}