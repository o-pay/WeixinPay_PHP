<?php
namespace Opay\Payment\Weixinpay\Traits;

/**
 * 加解密相關功能
 */
trait Encryptions
{
    /**
     * AES 加解密方式
     *
     * @var string
     */
    protected $method = 'AES-128-CBC';

    /**
     * AES 加密選項
     *
     * @var int
     */
    protected $options = OPENSSL_RAW_DATA;

    /**
    * AES 解密
    * @param  string $data
    * @param  string $key
    * @param  string $iv
    * @return string
    */
    public function aesDecrypt($data, $key, $iv)
    {
        $decoded = base64_decode($data);
        $decrypted = openssl_decrypt($decoded, $this->method, $key, $this->options, $iv);

        return $decrypted;
    }

    /**
    * AES 加密
    * @param  string $data
    * @param  string $key
    * @param  string $iv
    * @return string
    */
    public function aesEncrypt($data, $key, $iv)
    {
        $encrypted = openssl_encrypt($data, $this->method, $key, $this->options, $iv);
        $encrypted = base64_encode($encrypted);

        return $encrypted;
    }

    /**
    * JSON 轉陣列
    * @param  string $encoded
    * @return array
    */
    public function jsonDecode($encoded)
    {
        return json_decode($encoded, true); 
    }
    
    /**
    * 資料轉 JSON
    * @param  array  $data
    * @return string
    */
    public function jsonEncode($data)
    {
        return json_encode($data); 
    }

    /**
     * 編碼 URL 字串
     *
     * @param  mixed  $data
     * @return string
     */
    public function urlEncode($data)
    {
        $encoded = urlencode($data);

        // 取代為與 .net 相符字元
        $search = ['%2d', '%5f', '%2e', '%21', '%2a', '%28', '%29'];
        $replace = ['-', '_', '.', '!', '*', '(', ')'];
        $replaced = str_ireplace($search, $replace, $encoded);

        return $replaced;
    }

    /**
     * 解碼 URL 字串
     *
     * @param  mixed  $data
     * @return string
     */
    public function urlDecode($data)
    {
        return urldecode($data);
    }

    /**
     * 批次編碼 URL 字串
     *
     * @param  array $data
     * @return array
     */
    public function batchUrlEncode($data)
    {
        foreach ($data as $index => $value) {
            $data[$index] = $this->urlEncode($value);
        }

        return $data;
    }
}