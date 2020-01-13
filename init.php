<?php
$rootPath = dirname(__FILE__) . '/src';
$rulePath = $rootPath . '/Rules';
$traitPath = $rootPath . '/Traits';

// SDK
require($rootPath . '/Facades/Sdk.php');

// 驗證規則
require($rulePath . '/Common.php');
require($rulePath . '/CreateOrder.php');
unset($rulePath);

// Traits
require($traitPath . '/Common.php');
require($traitPath . '/Curl.php');
require($traitPath . '/Encryptions.php');
require($traitPath . '/HtmlCollectives.php');
require($traitPath . '/Validations.php');
unset($traitPath);

// 主要類別
require($rootPath . '/Base.php');
require($rootPath . '/CreateOrder.php');
require($rootPath . '/QueryTradeInfo.php');
unset($rootPath);
