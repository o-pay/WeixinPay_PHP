<?php
require_once('../vendor/autoload.php');

use Opay\Payment\Weixinpay\Facades\Sdk;

$siteRoot = 'https://' . $_SERVER['HTTP_HOST'];
$postData = [
    'MerchantID' => '2000132',
    'PlatformID' => '',
    'HashKey' => '5294y06JbISpM5x9',
    'HashIV' => 'v77hoKGq4kWxNNIS',
    'RqID' => uniqid('rqid_'),
    'Revision' => '1',
    'Items' => [
        [
            'Name' => '歐付寶黑芝麻豆漿',
            'Price' => 2000,
            'Currency' => '元',
            'Quantity' => 1,
        ],
        [
            'Name' => '歐付寶白豆漿',
            'Price' => -10,
            'Currency' => '元',
            'Quantity' => 2,
        ],
    ],
    'MerchantTradeNo' => uniqid('merchant_'),
    'ReturnURL' => $siteRoot . '/response.php',
    'ClientBackURL' => $siteRoot . '/thankyou.php',
];

try {
    // 建立訂單
    Sdk::createOrder($postData);
} catch (Exception $e) {
    echo $e->getMessage() . '<br>';
}