<?php
namespace Opay\Payment\Weixinpay;

use Opay\Payment\Weixinpay\Base;
use Opay\Payment\Weixinpay\Traits\Curl;

class QueryTradeInfo extends Base
{
    use Curl;

    /**
     * API URL
     *
     * @var array
     */
    protected $apiUrls = [
        'stage' => 'https://payment-stage.opay.tw/WxAPI/QueryTradeInfo',
        'production' => 'https://payment.opay.tw/WxAPI/QueryTradeInfo',
    ];

    /**
     * 傳入參數名稱與預設值
     *
     * @var array
     */
    protected $requestFields = [
        'MerchantID' => '',
        'PlatformID' => '',
        'HashKey' => '',
        'HashIV' => '',
        'Timestamp' => '',
        'RqID' => '',
        'Revision' => '',
        'MerchantTradeNo' => '',
    ];

    /**
     * 加密資料欄位與預設值
     *
     * @var array
     */
    protected $encryptFields = [
        'MerchantID' => '',
        'MerchantTradeNo' => '',
    ];

    /**
     * 加密後保留的欄位名稱
     *
     * @var array
     */
    protected $keepAfterEncryption = ['MerchantID'];
    
    /**
     * API 回傳資料欄位
     *
     * @var array
     */
    protected $responseFields = [
        'MerchantID' => '',
        'PlatformID' => '',
        'Timestamp' => '',
        'RqID' => '',
        'Revision' => '',
        'RtnCode' => '',
        'RtnMsg' => '',
        'Data' => '',
    ];

    /**
     * 解密後需 urldecode 欄位
     *
     * @var array
     */
    protected $urlDecodeFields = ['Data.ItemName'];
    
    /**
     * 驗證前資料處理
     *
     * @param  array $data
     * @return array
     */
    protected function filterBeforeValidateRequest($data)
    {
        // 設定交易時間
        $data['Timestamp'] = $this->timeNow;
    
        return $data;
    }

    /**
     * 驗證 Response 前資料處理
     *
     * @param  array $data
     * @return array
     */
    protected function filterBeforeValidateResponse($data)
    {
        // POST 數值欄位轉 Integer 以通過驗證
        $toIntList = ['Timestamp'];
        $filtered = $this->listForceToInt($data, $toIntList);

        return $filtered;
    }
    
    /**
     * 解密後處理
     *
     * @param  array $data
     * @return array
     */
    protected function filterAfterDecrypt($data)
    {
        // 解析需轉換欄位
        foreach ($this->urlDecodeFields as $field) {
            // 取得需解析欄位
            $pieces = explode('.', $field);
            
            // 編碼 URL 字串
            if (count($pieces) > 1) {
                $data[$pieces[0]][$pieces[1]] = $this->urlDecode($data[$pieces[0]][$pieces[1]]);
            } else {
                $data[$pieces[0]] = $this->urlDecode($data[$pieces[0]]);
            }
        }

        return $data;
    }

    /**
     * Post
     *
     * @return array
     */
    public function post()
    {
        // Server Post
        $serviceUrl = $this->getServiceUrl();
        $resultString = $this->serverPost($this->postData, $serviceUrl);
        parse_str($resultString, $resultArray);

        // 回應處理
        $merchantInfo = [];
        $response = $this->getResponse($merchantInfo, $resultArray);

        return $response;
    }
}