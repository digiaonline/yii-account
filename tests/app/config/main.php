<?php

require('bootstrap.php');

$basePath = dirname(__DIR__);

return array(
    'name' => 'Test Application',
    'basePath' => $basePath,
    'runtimePath' => "$basePath/runtime",
    'aliases' => array(
        'yii-account' => realpath("$basePath/../../src"),
        'vendor' => realpath("$basePath/../../vendor"),
    ),
    'modules' => array(
        'account' => array(
            'class' => 'application.modules.account.AccountModule',
            'numAllowedFailedLogins' => 1,
            'lockoutExpireTime' => 1,
            'passwordExpireTime' => 1,
        ),
    ),
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASS,
            'charset' => 'utf8',
        ),
    ),
);