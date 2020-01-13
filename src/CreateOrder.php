<?php
namespace Opay\Payment\Weixinpay;

use Opay\Payment\Weixinpay\Base;
use Opay\Payment\Weixinpay\Rules\CreateOrder as CreateOrderRules;
use Opay\Payment\Weixinpay\Traits\HtmlCollectives;

class CreateOrder extends Base
{
    use HtmlCollectives, CreateOrderRules;

    /**
     * API URL
     *
     * @var array
     */
    protected $apiUrls = [
        'stage' => 'https://payment-stage.opay.tw/WxAPI/Create',
        'production' => 'https://payment.opay.tw/WxAPI/Create',
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
        'Items' => '',
        'MerchantTradeNo' => '',
        'MerchantTradeDate' => '',
        'ReturnURL' => '',
        'Amount' => '',
        'ClientBackURL' => '',
    ];

    /**
     * 加密資料欄位與預設值
     *
     * @var array
     */
    protected $encryptFields = [
        'MerchantID' => '',
        'ItemName' => '',
        'MerchantTradeNo' => '',
        'MerchantTradeDate' => '',
        'ReturnURL' => '',
        'Amount' => '',
        'ClientBackURL' => '',
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
        'HashKey' => '',
        'HashIV' => '',
        'Timestamp' => '',
        'RqID' => '',
        'Revision' => '',
        'RtnCode' => '',
        'RtnMsg' => '',
        'Data' => '',
    ];

    /**
     * 解密前資料處理
     *
     * @param  array $data
     * @return array
     */
    protected function filterBeforeDecryptResponse($data)
    {
        // 取得 HashKey 和 HashIv
        $this->hashKey = $data['HashKey'];
        $this->hashIv = $data['HashIV'];
        $data = $this->unsetFields($data, ['HashKey', 'HashIV']);

        return $data;
    }


    /**
     * 加密前資料處理
     *
     * @param  array $data
     * @return array
     */
    protected function filterBeforeEncryptRequest($data)
    {
        $fromParent = parent::filterBeforeEncryptRequest($data);

        // 設定 ItemName
        $itemNames = $this->getItemNames($fromParent['Items']);
        $fromParent['ItemName'] = $itemNames;
        $fromParent = $this->unsetFields($fromParent, ['Items']);

        return $fromParent;
    }
    
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
        $data['MerchantTradeDate'] = date('Y-m-d H:i:s', $this->timeNow);
        
        // 設定商品明細預設值
        $data['Items'] = $this->getItemsWithDefault($data['Items']);

        // 設定交易總金額
        $data['Amount'] = $this->getAmount($data['Items']);
    
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
        $toIntList = ['Timestamp', 'RtnCode'];
        $filtered = $this->listForceToInt($data, $toIntList);

        return $filtered;
    }
    
    /**
     * 取得交易總金額
     *
     * @param  array $items
     * @return int
     */
    private function getAmount($items)
    {
        $amount = 0;
        foreach ($items as $details) {
            $amount += (int) $details['Price'] * (int) $details['Quantity'];
        }

        return $amount;
    }

    /**
     * 取得 Form HTML
     *
     * @return string
     */
    public function getHtml()
    {
        $serviceUrl = $this->getServiceUrl();
        $html = $this->generateAutoSubmitFormHtml($this->postData, $serviceUrl);

        return $html;
    }

    /**
     * 組合商品名稱
     *
     * @param  array $items
     * @return void
     */
    private function getItemNames($items)
    {
        $itemNames = '';
        foreach ($items as $details) {
            $itemNames .= vsprintf(
                '#%s %d %s x %u',
                $details
            );
        }
        $encoded = $this->urlEncode($itemNames);

        return $encoded;
    }

    /**
     * 設定商品明細預設值
     *
     * @param  array $items
     * @return array
     */
    private function getItemsWithDefault($items)
    {
        $data = [];

        $itemDetailFields = [
            'Name' => '',
            'Price' => '',
            'Currency' => '',
            'Quantity' => '',
        ];
        foreach ($items as $index => $details) {
            $data[$index] = $this->getInputs($details, $itemDetailFields);
        }

        return $data;
    }
}