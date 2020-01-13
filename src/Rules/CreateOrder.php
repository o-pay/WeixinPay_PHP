<?php
namespace Opay\Payment\Weixinpay\Rules;

/**
 * 建立訂單驗證規則
 */
trait CreateOrder
{
    /**
     * 檢查交易金額
     *
     * @param  mixed $value
     * @return void
     */
    protected function ruleAmount($value)
    {
        $name = $this->getValidateParameterName(__FUNCTION__);

        $this->isRequired($value, $name);
        $this->isInt($value, $name);
        $this->isPositive($value, $name);
        $this->isBetween($value, $name, 1, 5);
    }

    /**
     * 檢查 Client 端返回廠商網址
     *
     * @param  string $value
     * @return void
     */
    protected function ruleClientBackUrl($value)
    {
        $name = 'ClientBackURL';

        $this->isRequired($value, $name);
        $this->isString($value, $name);
        $this->isUrl($value, $name);
    }

    /**
     * 檢查商品幣別
     *
     * @param  mixed $value
     * @return void
     */
    protected function ruleCurrency($value)
    {
        $name = $this->getValidateParameterName(__FUNCTION__);

        $this->isRequired($value, $name);
        $this->isString($value, $name);
    }

    /**
     * 檢查商品明細
     *
     * @param  mixed $values
     * @return void
     */
    protected function ruleItems($values)
    {
        foreach ($values as $details) {
            $this->validateInputs($details);
        }
    }

    /**
     * 檢查商品名稱
     *
     * @param  mixed $value
     * @return void
     */
    protected function ruleName($value)
    {
        $name = $this->getValidateParameterName(__FUNCTION__);

        $this->isRequired($value, $name);
        $this->isString($value, $name);
    }

    /**
     * 檢查商品單價
     *
     * @param  mixed $value
     * @return void
     */
    protected function rulePrice($value)
    {
        $name = $this->getValidateParameterName(__FUNCTION__);

        $this->isRequired($value, $name);
        $this->isInt($value, $name);
    }

    /**
     * 檢查商品數量
     *
     * @param  mixed $value
     * @return void
     */
    protected function ruleQuantity($value)
    {
        $name = $this->getValidateParameterName(__FUNCTION__);

        $this->isRequired($value, $name);
        $this->isInt($value, $name);
    }

    /**
     * 檢查廠商交易時間
     *
     * @param  mixed  $value
     * @return void
     */
    protected function ruleMerchantTradeDate($value)
    {
        $name = $this->getValidateParameterName(__FUNCTION__);

        $this->isRequired($value, $name);
        $this->isDatetime($value, $name, 'Y-m-d H:i:s');
    }

    /**
     * 檢查 ReturnURL
     *
     * @param  mixed  $value
     * @return void
     */
    protected function ruleReturnUrl($value)
    {
        $name = 'ReturnURL';

        $this->isRequired($value, $name);
        $this->isString($value, $name);
        $this->isUrl($value, $name);
    }
}
