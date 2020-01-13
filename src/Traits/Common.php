<?php
namespace Opay\Payment\Weixinpay\Traits;

/**
 * 通用雜項功能
 */
trait Common
{
    /**
     * 釋放變數
     *
     * @param  array $data
     * @param  array $fields
     * @return void
     */
    protected function unsetFields($data, $fields)
    {
        foreach ($fields as $name) {
            unset($data[$name]);
        }

        return $data;
    }

    /**
     * 型別強制轉數值
     *
     * @param  array $data
     * @param  array $list
     * @return void
     */
    public function listForceToInt($data, $list)
    {
        foreach ($list as $name) {
            $data[$name] = (int) $data[$name];
        }

        return $data;
    }
    
}
