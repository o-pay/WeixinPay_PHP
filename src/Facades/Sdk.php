<?php
namespace Opay\Payment\Weixinpay\Facades;

use Opay\Payment\Weixinpay\CreateOrder;
use Opay\Payment\Weixinpay\QueryTradeInfo;

class Sdk
{
    /**
     * SDK 版本
     * @var string
     */
    const VERSION = '1.0.190910';

    /**
     * 取得 SDK 版本
     *
     * @return string
     */
    public static function getVersion()
    {
        return self::VERSION;
    }

    /**
     * 建立訂單
     * 
     * @param  array $data
     * @return void
     */
    public static function createOrder($data)
    {
        $instance = new CreateOrder();
        $html = $instance->prepareRequest($data)->getHtml();

        echo $html;
    }

    /**
     * 付款通知
     * 
     * @param  array $merchantInfo
     * @param  array $postData
     * @return array
     */
    public static function createOrderResponse($merchantInfo, $postData)
    {
        $instance = new CreateOrder();
        $response = $instance->getResponse($merchantInfo, $postData);

        return $response;
    }

    /**
     * 查詢訂單
     * 
     * @param  array $postData
     * @return array
     */
    public static function queryTradeInfo($postData)
    {
        $instance = new QueryTradeInfo();
        $response = $instance->prepareRequest($postData)->post();

        return $response;
    }
}