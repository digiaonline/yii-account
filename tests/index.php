<?php

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL^E_NOTICE);

defined('YII_DEBUG') or define('YII_DEBUG', true);

$root = dirname(__DIR__);
$tests = __DIR__;
$vendor = dirname(__DIR__) . '/vendor';

require("$vendor/autoload.php");
require("$vendor/yiisoft/yii/framework/yii.php");

$basePath = dirname(__DIR__) . '/src';

$config = array(
    'basePath' => $basePath,
    'defaultController' => 'account',
    'runtimePath' => "$tests/_data/runtime",
    'viewPath' => "$basePath/views",
    'modules' => array(
        'account' => array(
            'class' => '\nordsoftware\yii_account\AccountModule',
        ),
    ),
);

Yii::createWebApplication($config)->run();