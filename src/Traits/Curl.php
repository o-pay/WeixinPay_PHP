<?php
namespace Opay\Payment\Weixinpay\Traits;

/**
 * CURL
 */
trait Curl
{
    /**
    * Server Post
    * @param array  $parameters
    * @param string $url
    * @return void
    */
    public function serverPost($parameters, $url)
    {

        $curlHandle = curl_init();

        if ($curlHandle === false) {
            throw new Exception('curl failed to initialize');
        }

        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_HEADER, false);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curlHandle, CURLOPT_POST, true);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($parameters));
        $result = curl_exec($curlHandle);

        if ($result === false) {
            throw new Exception(curl_error($curlHandle), curl_errno($curlHandle));
        }

        curl_close($curlHandle);

        return $result; 
    }
}