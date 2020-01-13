<?php
require_once('../vendor/autoload.php');

use Opay\Payment\Weixinpay\Facades\Sdk;

$postData = [
    'MerchantID' => '2000132',
    'PlatformID' => '',
    'HashKey' => '5294y06JbISpM5x9',
    'HashIV' => 'v77hoKGq4kWxNNIS',
    'RqID' => uniqid('rqid_'),
    'Revision' => '1',
    'MerchantTradeNo' => 'merchant_5d8d6287c28',
];

try {
    // 查詢訂單
    $response = Sdk::queryTradeInfo($postData);

    // 查詢訂單結果相關處理
    echo '<pre>' . print_r($response, true) . '</pre>';
} catch (Exception $e) {
    echo $e->getMessage() . '<br>';
}