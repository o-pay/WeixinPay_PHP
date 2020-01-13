<?php
require_once('../vendor/autoload.php');

use Opay\Payment\Weixinpay\Facades\Sdk;

$merchantInfo = [
    'HashKey' => '5294y06JbISpM5x9',
    'HashIV' => 'v77hoKGq4kWxNNIS',
];

try {
    // 取得付款結果
    $response = Sdk::createOrderResponse($merchantInfo, $_POST);

    // 接收付款結果相關處理
    echo '<pre>' . print_r($response, true) . '</pre>';
} catch (Exception $e) {
    echo $e->getMessage() . '<br>';
}