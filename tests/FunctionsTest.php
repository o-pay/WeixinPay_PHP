<?php

use PHPUnit\Framework\TestCase;
use Opay\Payment\Weixinpay\CreateOrder;
use Opay\Payment\Weixinpay\QueryTradeInfo;

final class FunctionTest extends TestCase
{
    /**
     * 特店資訊
     *
     * @var array
     */
    protected $merchantInfo = [
        'general' => [
            'MerchantID' => '2000132',
            'PlatformID' => '',
            'HashKey' => '5294y06JbISpM5x9',
            'HashIV' => 'v77hoKGq4kWxNNIS',
        ],
        'platform' => [
            'MerchantID' => '2012441',
            'PlatformID' => '2012441',
            'HashKey' => 'bkuAEQufy2bpEng1',
            'HashIV' => 'B0lzARI9ZSdhW9jg',
        ]
    ];

    /**
     * 相關 URL
     *
     * @var array
     */
    protected $parameterUrls = [];

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $siteRoot = 'https://www.opay-sdk-weixinpay-test.com.tw';
        $this->parameterUrls = [
            'ReturnURL' => $siteRoot . '/response',
            'ClientBackURL' => $siteRoot . '/thankyou',
        ];
    }

    /**
     * 產生亂數字串
     *
     * @param  integer $length
     * @return string
     */
    protected function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function testCreateGeneralOrder()
    {
        // 輸入
        $instance = new CreateOrder();
        $postData = [
            'RqID' => $this->generateRandomString(64),
            'Revision' => '1',
            'Items' => [
                [
                    'Name' => '歐付寶黑芝麻豆漿',
                    'Price' => 50,
                    'Currency' => '元',
                    'Quantity' => 1,
                ],
                [
                    'Name' => '歐付寶白豆漿',
                    'Price' => -10,
                    'Currency' => '元',
                    'Quantity' => 2,
                ],
            ],
            'MerchantTradeNo' => $this->generateRandomString(64),
            'ReturnURL' => $this->parameterUrls['ReturnURL'],
            'ClientBackURL' => $this->parameterUrls['ClientBackURL'],
        ];
        $merged = array_merge($this->merchantInfo['general'], $postData);

        // 執行
        $html = $instance->prepareRequest($merged)->getHtml();
        
        // 檢查執行結果
        $this->assertStringStartsWith('<!DOCTYPE html>', $html);
    }

    public function testCreatePlatformOrder()
    {
        // 輸入
        $instance = new CreateOrder();
        $postData = [
            'RqID' => $this->generateRandomString(64),
            'Revision' => '1',
            'Items' => [
                [
                    'Name' => '歐付寶黑芝麻豆漿',
                    'Price' => 50,
                    'Currency' => '元',
                    'Quantity' => 1,
                ],
                [
                    'Name' => '歐付寶白豆漿',
                    'Price' => -10,
                    'Currency' => '元',
                    'Quantity' => 2,
                ],
            ],
            'MerchantTradeNo' => $this->generateRandomString(64),
            'ReturnURL' => $this->parameterUrls['ReturnURL'],
            'ClientBackURL' => $this->parameterUrls['ClientBackURL'],
        ];
        $merged = array_merge($this->merchantInfo['platform'], $postData);

        // 執行
        $html = $instance->prepareRequest($merged)->getHtml();
        
        // 檢查執行結果
        $this->assertStringStartsWith('<!DOCTYPE html>', $html);
    }

    public function testReceiveGeneralOrderResponse()
    {
        // 輸入
        $merchantId = $this->merchantInfo['general']['MerchantID'];
        $instance = new CreateOrder();
        $mockData = [
            'MerchantID' => $merchantId,
            'Timestamp' => time(),
            'RqID' => $this->generateRandomString(64),
            'Revision' => '1',
            'RtnCode' => 1,
            'RtnMsg' => $this->generateRandomString(200),
            'Data' => 'maF/BEw5WavQw+CeD6HpGQzsejKGyrjNOYOP6sbDBtqbtIpUHqfQVw8D/somcfvnMs1QCRB53FMhjYdDm9HVGnyGd+aH9bkrAuL2/3T6p9TRKEACimlkLRQJhq77Go7AIGk9vLM77ugRKfDQjjCS4pYF30KZernfyoPwFUVzJIUGhoThMgXj2rSZ8mN/4cUczHJzPVM1hv6A/JxjD11+kbK7pDj/KJwfmRg5zvkQWnW6a0eJk7ah/rx8nyklIDzJmOqo5XiexSotXYgw0ZoYsDH736qxqsl5XyaL+u3fZSenfl+nuJrc/0BG/oG3CAiQ7H5obK1Lnu3aDUU17yecCcdkUgtQOq8ToyekpGG0N78Q47GpBfGevFYTgdVfYwLeSF6UmzcjfsDE+1N+e1gKg/sCYnOjEknLjS33/PMYrDXXuNwybVl1E4IbNUpgGHoy/vO97pE4alKersc2Semh8ZoCzWd+S7nPsWzozfH9qzZ/hHEz1ErHHUyx/Ho3047FOWyu23bt1vk4CokSRVbJqURddMy83Nw9HYJXgGLzc7EFwMboLf+l+l2H5WPIxcdRcivBXYIItEkmvx7S3MrXklbTb0+6nZFPsOX9F/cBd2JJRRC1a6NovLbXN2xIvPoM',
        ];
        
        // 執行
        $response = $instance->getResponse($this->merchantInfo['general'], $mockData);
        
        // 執行結果檢查
        $this->assertEquals($merchantId, $response['Data']['MerchantID']);
    }

    public function testQueryTradeInfo()
    {
        // 輸入
        $merchantTradeNo = '20190911054555';
        $instance = new QueryTradeInfo();
        $postData = [
            'RqID' => $this->generateRandomString(64),
            'Revision' => '1',
            'MerchantTradeNo' => $merchantTradeNo,
        ];
        $merged = array_merge($this->merchantInfo['general'], $postData);

        // 執行 
        $response = $instance->prepareRequest($merged)->post();

        // 執行結果檢查
        $this->assertEquals($merchantTradeNo, $response['Data']['MerchantTradeNo']);
    }

    /**
     * 測試加總金額項目不允許1~5元
     * @param  integer $length
     * @return string
     */
    public function testCreateGeneralAmountRangeOrderException()
    {
        // 輸入
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Amount can not between 1 and 5');

        $instance = new CreateOrder();
        $postData = [
            'RqID' => $this->generateRandomString(64),
            'Revision' => '1',
            'Items' => [
                [
                    'Name' => '歐付寶黑芝麻豆漿',
                    'Price' => 1,
                    'Currency' => '元',
                    'Quantity' => 1,
                ],
                [
                    'Name' => '歐付寶白豆漿',
                    'Price' => 2,
                    'Currency' => '元',
                    'Quantity' => 2,
                ],
            ],
            'MerchantTradeNo' => $this->generateRandomString(64),
            'ReturnURL' => $this->parameterUrls['ReturnURL'],
            'ClientBackURL' => $this->parameterUrls['ClientBackURL'],
        ];
        $merged = array_merge($this->merchantInfo['general'], $postData);

        // 執行
        $html = $instance->prepareRequest($merged)->getHtml();
    }

    /**
     * 測試加總金額項目不允許負數
     *
     * @param  integer $length
     * @return string
     */
    public function testCreateGeneralNegativeAmountOrder()
    {
        // 輸入
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Amount must be a positive integer.');

        $instance = new CreateOrder();
        $postData = [
            'RqID' => $this->generateRandomString(64),
            'Revision' => '1',
            'Items' => [
                [
                    'Name' => '歐付寶黑芝麻豆漿',
                    'Price' => -10,
                    'Currency' => '元',
                    'Quantity' => 1,
                ],
                [
                    'Name' => '歐付寶白豆漿',
                    'Price' => 2,
                    'Currency' => '元',
                    'Quantity' => 2,
                ],
            ],
            'MerchantTradeNo' => $this->generateRandomString(64),
            'ReturnURL' => $this->parameterUrls['ReturnURL'],
            'ClientBackURL' => $this->parameterUrls['ClientBackURL'],
        ];
        $merged = array_merge($this->merchantInfo['general'], $postData);

        // 執行
        $html = $instance->prepareRequest($merged)->getHtml();
        
        // 檢查執行結果
        $this->assertStringStartsWith('<!DOCTYPE html>', $html);
    }


    /**
     * 測試加總金額為零允許交易
     */
    public function testCreateGeneralOrderZeroAmountOrder()
    {
        // 輸入
        $instance = new CreateOrder();
        $postData = [
            'RqID' => $this->generateRandomString(64),
            'Revision' => '1',
            'Items' => [
                [
                    'Name' => '歐付寶黑芝麻豆漿',
                    'Price' => 0,
                    'Currency' => '元',
                    'Quantity' => 1,
                ],
                [
                    'Name' => '歐付寶白豆漿',
                    'Price' => 0,
                    'Currency' => '元',
                    'Quantity' => 2,
                ],
            ],
            'MerchantTradeNo' => $this->generateRandomString(64),
            'ReturnURL' => $this->parameterUrls['ReturnURL'],
            'ClientBackURL' => $this->parameterUrls['ClientBackURL'],
        ];
        $merged = array_merge($this->merchantInfo['general'], $postData);

        // 執行
        $html = $instance->prepareRequest($merged)->getHtml();
        
        // 檢查執行結果
        $this->assertStringStartsWith('<!DOCTYPE html>', $html);
    }
}