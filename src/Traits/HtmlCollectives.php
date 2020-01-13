<?php
namespace Opay\Payment\Weixinpay\Traits;

/**
 * HTML 相關功能
 */
trait HtmlCollectives
{
    /**
    * 產生自動提交表單 HTML
    *
    * @param  array  $parameters
    * @param  string $url
    * @return string
    */
    public function generateAutoSubmitFormHtml($parameters, $url)
    {
        $formName = '__OpayWeiXinPayForm';
        $url = $this->escapeHtml($url);

        $html =  '<!DOCTYPE html>';
        $html .= '<html>';
        $html .=     '<head>';
        $html .=         '<meta charset="utf-8">';
        $html .=     '</head>';
        $html .=     '<body>';
        $html .=         '<form id="' . $formName . '" method="post" action="' . $url . '">';

        if (count($parameters) > 0) {
            foreach ($parameters as $key => $value) {
                $key = $this->escapeHtml($key);
                $value = $this->escapeHtml($value);
                $html .=         '<input type="hidden" name="' . $key . '" value="' . $value . '">';
            }
        }

        $html .=         '</form>';
        $html .=         '<script type="text/javascript">';
        $html .=             'document.getElementById("' . $formName . '").submit();';
        $html .=         '</script>';
        $html .=     '</body>';
        $html .= '</html>';

        return $html;
    }

    /**
     * 轉換為 HTML 實體，避免 XSS 攻擊
     *
     * @param  mixed $value
     * @return string
     */
    public function escapeHtml($value)
    {
        $casted = (string) $value;
        $converted = htmlentities($casted);

        return $converted;
    }
}