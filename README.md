## 目錄
* [環境需求](#環境需求)
* [開始使用](#開始使用)
* [文件](#文件)
* [目錄結構](#目錄結構)

## 環境需求
PHP >= 5.6

## 開始使用
[Composer](https://getcomposer.org/doc/00-intro.md)
```bash
composer require opay/sdk-weixinpay
```
```php
require_once('vendor/autoload.php');
```
or

手動下載最新版本，再 include init.php
```php
require_once('init.php');
```

## 文件
請參考[範例](examples/)。 Composer 使用者請將 examples 目錄移到與 vendor 同一層或修改 include autoload 路徑使用。

## 目錄結構
    ├── examples/       # 範例
    ├── src/
    │   └── Facades/    # SDK
    │   └── Rules/      # 驗證規則
    │   └── Traits/     # 其他功能
    ├── tests/          # 功能測試
    └── README.md       # 說明