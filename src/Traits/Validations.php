<?php
namespace Opay\Payment\Weixinpay\Traits;

use \Exception;

/**
 * 驗證相關功能
 */
trait Validations
{
    /**
     * 驗證規則前輟
     *
     * @var string
     */
    protected $rulePrefix = 'rule';

    /**
     * 取得驗證參數名稱
     *
     * @param  string $value
     * @return void
     */
    protected function getValidateParameterName($value)
    {
        $result = str_replace($this->rulePrefix, '', $value);

        return $result;
    }

    /**
     * 取得驗證方法名稱
     *
     * @param  string $name
     * @return void
     */
    protected function getValidateMethodName($name)
    {
        $methodName = $this->rulePrefix . $name;

        return $methodName;
    }

    /**
     * 檢查是否為數值型態
     *
     * @param  mixed  $value
     * @param  string $name
     * @return void
     */
    protected function isInt($value, $name)
    {
        if (is_int($value) === false) {
            throw new Exception($name . ' must be a integer.');
        }
    }

    /**
     * 檢查是否為正數
     *
     * @param  mixed  $value
     * @param  string $name
     * @return void
     */
    protected function isPositive($value, $name)
    {
        if (is_int($value) === false || $value < 0 ) {
            throw new Exception($name . ' must be a positive integer.');
        }
    }

    /**
     * 檢查是否為字串型態
     *
     * @param  mixed  $value
     * @param  string $name
     * @return void
     */
    protected function isString($value, $name)
    {
        if (is_string($value) === false) {
            throw new Exception($name . ' must be a string.');
        }
    }

    /**
     * 檢查是否為必填
     *
     * @param  mixed  $value
     * @param  string $name
     * @return void
     */
    protected function isRequired($value, $name)
    {
        if ($value === '') {
            throw new Exception($name . ' is required.');
        }
    }

    /**
     * 檢查是否小於或等於指定長度
     *
     * @param  mixed  $value
     * @param  string $name
     * @param  int    $length
     * @return void
     */
    protected function isShorterThenOrEqual($value, $name, $length)
    {
        if (strlen($value) > $length) {
            throw new Exception($name . ' max length is ' . $length . '.');
        }
    }

    /**
     * 檢查是否大於或等於指定值
     *
     * @param  mixed  $value
     * @param  string $name
     * @param  int    $min
     * @return void
     */
    protected function isGreaterThenOrEqual($value, $name, $min)
    {
        if ($value < $min) {
            throw new Exception($name . ' minimum is ' . $min . '.');
        }
    }

    /**
     * 檢查是否數值在特定區間內
     *
     * @param  mixed  $value
     * @param  string $name
     * @param  int    $min
     * @param  int    $max
     * @return void
     */
    protected function isBetween($value, $name, $min, $max)
    {
        if ($min <= $value && $value <= $max) {
            throw new Exception($name . ' can not between ' . $min . ' and '. $max . '.');
        }
    } 

    /**
     * 檢查是否為日期時間
     *
     * @param  mixed  $value
     * @param  string $name
     * @param  string $format
     * @return void
     */
    protected function isDatetime($value, $name, $format = 'Y-m-d H:i:s')
    {
        if (date($format, strtotime($value)) !== $value) {
            throw new Exception($name . ' is not valid datetime.');
        }
    }


    /**
     * 檢查是否為 URL
     *
     * @param  mixed  $value
     * @param  string $name
     * @return void
     */
    protected function isUrl($value, $name)
    {
        /*
         * This pattern is derived from Symfony\Component\Validator\Constraints\UrlValidator (2.7.4).
         *
         * (c) Fabien Potencier <fabien@symfony.com> http://symfony.com
         */
        $pattern = '~^
            ((aaa|aaas|about|acap|acct|acr|adiumxtra|afp|afs|aim|apt|attachment|aw|barion|beshare|bitcoin|blob|bolo|callto|cap|chrome|chrome-extension|cid|coap|coaps|com-eventbrite-attendee|content|crid|cvs|data|dav|dict|dlna-playcontainer|dlna-playsingle|dns|dntp|dtn|dvb|ed2k|example|facetime|fax|feed|feedready|file|filesystem|finger|fish|ftp|geo|gg|git|gizmoproject|go|gopher|gtalk|h323|ham|hcp|http|https|iax|icap|icon|im|imap|info|iotdisco|ipn|ipp|ipps|irc|irc6|ircs|iris|iris.beep|iris.lwz|iris.xpc|iris.xpcs|itms|jabber|jar|jms|keyparc|lastfm|ldap|ldaps|magnet|mailserver|mailto|maps|market|message|mid|mms|modem|ms-help|ms-settings|ms-settings-airplanemode|ms-settings-bluetooth|ms-settings-camera|ms-settings-cellular|ms-settings-cloudstorage|ms-settings-emailandaccounts|ms-settings-language|ms-settings-location|ms-settings-lock|ms-settings-nfctransactions|ms-settings-notifications|ms-settings-power|ms-settings-privacy|ms-settings-proximity|ms-settings-screenrotation|ms-settings-wifi|ms-settings-workplace|msnim|msrp|msrps|mtqp|mumble|mupdate|mvn|news|nfs|ni|nih|nntp|notes|oid|opaquelocktoken|pack|palm|paparazzi|pkcs11|platform|pop|pres|prospero|proxy|psyc|query|redis|rediss|reload|res|resource|rmi|rsync|rtmfp|rtmp|rtsp|rtsps|rtspu|secondlife|s3|service|session|sftp|sgn|shttp|sieve|sip|sips|skype|smb|sms|smtp|snews|snmp|soap.beep|soap.beeps|soldat|spotify|ssh|steam|stun|stuns|submit|svn|tag|teamspeak|tel|teliaeid|telnet|tftp|things|thismessage|tip|tn3270|turn|turns|tv|udp|unreal|urn|ut2004|vemmi|ventrilo|videotex|view-source|wais|webcal|ws|wss|wtai|wyciwyg|xcon|xcon-userid|xfire|xmlrpc\.beep|xmlrpc.beeps|xmpp|xri|ymsgr|z39\.50|z39\.50r|z39\.50s))://                                 # protocol
            (([\pL\pN-]+:)?([\pL\pN-]+)@)?          # basic auth
            (
                ([\pL\pN\pS\-\.])+(\.?([\pL]|xn\-\-[\pL\pN-]+)+\.?) # a domain name
                    |                                              # or
                \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}                 # an IP address
                    |                                              # or
                \[
                    (?:(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,1}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,2}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,3}(?:(?:[0-9a-f]{1,4})))?::(?:(?:[0-9a-f]{1,4})):)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,4}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,5}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,6}(?:(?:[0-9a-f]{1,4})))?::))))
                \]  # an IPv6 address
            )
            (:[0-9]+)?                              # a port (optional)
            (/?|/\S+|\?\S*|\#\S*)                   # a /, nothing, a / with something, a query or a fragment
        $~ixu';

        if (preg_match($pattern, $value) === 0) {
            throw new Exception($name . ' is not valid url.');
        }
    }
}