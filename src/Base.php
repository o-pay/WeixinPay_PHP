<?php
namespace Opay\Payment\Weixinpay;

use Opay\Payment\Weixinpay\Rules\Common as CommonRules;
use Opay\Payment\Weixinpay\Traits\Encryptions;
use Opay\Payment\Weixinpay\Traits\Validations;
use Opay\Payment\Weixinpay\Traits\Common as CommonHelpers;

/**
* 基礎類別
*/
abstract class Base
{
    use Encryptions, Validations, CommonRules, CommonHelpers;

    /**
     * 加密資料欄位與預設值
     *
     * @var array
     */
    protected $encryptFields = [];

    /**
     * 加密資料欄位名稱
     *
     * @var string
     */
    protected $encryptAs = 'Data';

    /**
     * 解密資料欄位名稱
     */
    protected $decryptField = 'Data';

    /**
     * Hash IV
     *
     * @var string
     */
    protected $hashIv;

    /**
     * Hash Key
     *
     * @var string
     */
    protected $hashKey;

    /**
     * Request 參數名稱與預設值
     *
     * @var array
     */
    protected $requestFields = [];

    /**
     * 加密後保留的欄位名稱
     *
     * @var array
     */
    protected $keepAfterEncryption = [];

    /**
     * 傳送前需 urlencode 欄位
     *
     * @var array
     */
    protected $urlEncodeFields = ['Data' => ''];

    /**
     * 解密後需 urldecode 欄位
     *
     * @var array
     */
    protected $urlDecodeFields = [];

    /**
     * API URL
     *
     * @var array
     */
    protected $apiUrls = [];

    /**
     * Post API 資料
     */
    protected $postData = [];

    /**
     * API 回傳資料欄位
     *
     * @var array
     */
    protected $responseFields = [];

    /**
     * 輸入參數關鍵字
     *
     * @var array
     */
    protected $argStrings = ['ID', 'URL', 'IV'];

    /**
     * 程式處理參數關鍵字
     *
     * @var array
     */
    protected $processStrings = ['Id', 'Url', 'Iv'];

    public function __construct()
    {
        $this->timeNow = time();
    }

    /**
     * 解密資料
     *
     * @param  array  $data
     * @param  string $field
     * @return array
     */
    protected function decryptData($data, $field)
    {
        // 解密
        $decrypted = $this->aesDecrypt($data[$field], $this->hashKey, $this->hashIv);
        $decoded = $this->jsonDecode($decrypted);
        $data[$field] = $decoded;

        return $data;
    }

    /**
     * 加密資料
     *
     * @param  array  $data
     * @param  array  $fields
     * @param  string $as
     * @param  array  $keep
     * @return string
     */
    protected function encryptDataAs($data, $fields, $as, $keep)
    {
        // 取得加密資料
        $inputs = $this->getInputs($data, $fields);

        // 加密
        $jsonFormat = $this->jsonEncode($inputs);
        $encrypted = $this->aesEncrypt($jsonFormat, $this->hashKey, $this->hashIv);

        $data[$as] = $encrypted;

        // 移除原始資料
        $removeFields = array_keys($fields);
        $unsetFields = array_diff($removeFields, $keep);
        $data = $this->unsetFields($data, $unsetFields);

        return $data;
    }
    
    /**
     * 解密後處理
     *
     * @param  array $data
     * @return array
     */
    protected function filterAfterDecrypt($data)
    {
        return $data;
    }

    /**
     * 解密前資料處理
     *
     * @param  array $data
     * @return array
     */
    protected function filterBeforeDecryptResponse($data)
    {
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
        // 取得 MerchantId
        $this->merchantId = $data['MerchantID'];

        // 取得 HashKey 和 HashIv
        $this->hashKey = $data['HashKey'];
        $this->hashIv = $data['HashIV'];
        $data = $this->unsetFields($data, ['HashKey', 'HashIV']);

        return $data;
    }

    /**
     * 取得 Response 資料前處理
     *
     * @param  array $merchantInfo
     * @param  array $data
     * @return array
     */
    protected function filterBeforeGetResponseInputs($merchantInfo, $data)
    {
        /**
         * 合併廠商資訊與 API 回應
         * 注意 merge 順序，有相同欄位以 API 回應資料為主(Ex: MerchantID)
         */
        $filtered = array_merge($merchantInfo, $data);

        return $filtered;
    }
    
    /**
     * Post 前處理
     *
     * @param  array $data
     * @return array
     */
    protected function filterBeforePost($data)
    {
        $inputs = $this->getInputs($data, $this->urlEncodeFields);
        $encoded = $this->batchUrlEncode($inputs);
        $merged = array_merge($data, $encoded);

        return $merged;
    }

    /**
     * 驗證 Request 前資料處理
     *
     * @param  array $data
     * @return array
     */
    protected function filterBeforeValidateRequest($data)
    {
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
        return $data;
    }

    /**
     * 取得指定欄位資料，並給預設值，避免出現 undefined index
     *
     * @param  array $inputs
     * @return array
     */
    protected function getInputs($inputs, $fields)
    {
        $data = [];

        foreach ($fields as $name => $default) {
            if (isset($inputs[$name]) === false) {
                $data[$name] = $default;
            } else {
                $value = $inputs[$name];
                $valueFields = lcfirst($name) . 'Fields';
                if (is_array($value) === true && isset($this->$valueFields) === true) {
                    // 未使用，暫時保留
                    $data[$name] = $this->getInputs($value, $this->$valueFields);
                } else {
                    $data[$name] = $value;
                }
            }
        }

        return $data;
    }

    /**
     * 取得 Response
     *
     * @param  array $merchantInfo
     * @param  array $data
     * @return void
     */
    public function getResponse($merchantInfo, $data)
    {
        // 取得輸入資料前處理
        $filtered = $this->filterBeforeGetResponseInputs($merchantInfo, $data);

        // 取得輸入資料
        $inputs = $this->getInputs($filtered, $this->responseFields);

        // 驗證資料前處理
        $filtered = $this->filterBeforeValidateResponse($inputs);
        
        // 解密前處理
        $filtered = $this->filterBeforeDecryptResponse($filtered);

        // 解密資料
        $decrypted = $this->decryptData($filtered, $this->decryptField);

        // 解密後處理
        $filtered = $this->filterAfterDecrypt($decrypted);

        return $filtered;
    }

    /**
     * 取得 API URL
     *
     * @return string
     */
    protected function getServiceUrl()
    {
        if ($this->isTestMode() === true) {
            return $this->apiUrls['stage'];
        } else {
            return $this->apiUrls['production'];
        }
    }

    /**
     * 是否使用測試模式
     *
     * @return boolean
     */
    protected function isTestMode()
    {
        $stageMerchantId =[
            'general' => '2000132',
            'platform' => '2012441',
        ];
        $result = in_array($this->merchantId, $stageMerchantId, true);

        return $result;
    }

    /**
     * Request 資料準備
     *
     * @param  array $data
     * @return \Opay\Payment\Weixinpay\Base
     */
    public function prepareRequest($data)
    {
        // 取得輸入資料
        $inputs = $this->getInputs($data, $this->requestFields);

        // 驗證前處理
        $filtered = $this->filterBeforeValidateRequest($inputs);

        // 資料驗證
        $this->validateInputs($filtered);

        // 加密前處理
        $filtered = $this->filterBeforeEncryptRequest($filtered);

        // 資料加密
        $encrypted = $this->encryptDataAs(
            $filtered,
            $this->encryptFields,
            $this->encryptAs,
            $this->keepAfterEncryption
        );

        // 傳送前處理
        $filtered = $this->filterBeforePost($encrypted);

        // 設定 POST 參數
        $this->postData = $filtered;

        return $this;
    }

    /**
     *  輸入資料驗證
     *
     * @param  array $data
     * @return void
     */
    protected function validateInputs($data)
    {
        foreach ($data as $name => $value) {
            $replaced = str_replace($this->argStrings, $this->processStrings, $name);
            $method = $this->getValidateMethodName($replaced);
            $this->$method($value);
        }
    }
}