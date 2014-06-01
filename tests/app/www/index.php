<?php

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL^E_NOTICE);

defined('YII_DEBUG') or define('YII_DEBUG', true);

$tests = dirname(__DIR__) . '/..';
$vendor = dirname(__DIR__) . '/../../vendor';

require("$vendor/autoload.php");
require("$vendor/yiisoft/yii/framework/yii.php");

$config = require("$tests/app/config/web.php");

Yii::createWebApplication($config)->run();