<?php
namespace Opay\Payment\Weixinpay\Rules;

/**
 * 通用驗證規則
 */
trait Common
{
    /**
     * 檢查加密 Data
     *
     * @param  mixed  $value
     * @return void
     */
    protected function ruleData($value)
    {
        $name = $this->getValidateParameterName(__FUNCTION__);

        $this->isRequired($value, $name);
        $this->isString($value, $name);
    }
    
    /**
     * 檢查 HashIV
     *
     * @param  mixed $value HashIV
     * @return void
     */
    protected function ruleHashIv($value)
    {
        $name = 'HashIV';

        $this->isRequired($value, $name);
        $this->isString($value, $name);
    }

    /**
     * 檢查 HashKey
     *
     * @param  mixed $value HashKey
     * @return void
     */
    protected function ruleHashKey($value)
    {
        $name = $this->getValidateParameterName(__FUNCTION__);

        $this->isRequired($value, $name);
        $this->isString($value, $name);
    }

    /**
     * 檢查 MerchantID
     *
     * @param  mixed  $value
     * @return void
     */
    protected function ruleMerchantId($value)
    {
        $name = 'MerchantID';

        $this->isString($value, $name);
        $this->isRequired($value, $name);
        $this->isShorterThenOrEqual($value, $name, 10);
    }

    /**
     * 檢查廠商交易編號
     *
     * @param  mixed  $value
     * @return void
     */
    protected function ruleMerchantTradeNo($value)
    {
        $name = $this->getValidateParameterName(__FUNCTION__);

        $this->isRequired($value, $name);
        $this->isString($value, $name);
        $this->isShorterThenOrEqual($value, $name, 64);
    }

    /**
     * 檢查 PlatformID
     *
     * @param  mixed  $value
     * @return void
     */
    protected function rulePlatformId($value)
    {
        $name = 'PlatformID';

        $this->isString($value, $name);
        $this->isShorterThenOrEqual($value, $name, 10);
    }

    /**
     * 檢查串接版號
     *
     * @param  mixed  $value
     * @return void
     */
    protected function ruleRevision($value)
    {
        $name = $this->getValidateParameterName(__FUNCTION__);

        $this->isString($value, $name);
        $this->isRequired($value, $name);
        $this->isShorterThenOrEqual($value, $name, 10);
    }

    /**
     * 檢查 RqID
     *
     * @param  mixed  $value
     * @return void
     */
    protected function ruleRqId($value)
    {
        $name = 'RqID';

        $this->isString($value, $name);
        $this->isRequired($value, $name);
        $this->isShorterThenOrEqual($value, $name, 64);
    }

    /**
     * 檢查 RtnCode
     *
     * @param  mixed  $value
     * @return void
     */
    protected function ruleRtnCode($value)
    {
        $name = $this->getValidateParameterName(__FUNCTION__);

        $this->isRequired($value, $name);
        $this->isInt($value, $name);
    }

    /**
     * 檢查 RtnMsg
     *
     * @param  mixed  $value
     * @return void
     */
    protected function ruleRtnMsg($value)
    {
        $name = $this->getValidateParameterName(__FUNCTION__);

        $this->isRequired($value, $name);
        $this->isString($value, $name);
        $this->isShorterThenOrEqual($value, $name, 200);
    }

    /**
     * 檢查 Timestamp
     *
     * @param  mixed  $value
     * @return void
     */
    protected function ruleTimestamp($value)
    {
        $name = $this->getValidateParameterName(__FUNCTION__);

        $this->isRequired($value, $name);
        $this->isInt($value, $name);
    }
}
