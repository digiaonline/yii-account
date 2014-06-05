<?php

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
            'passwordExpireTime' => 1,
        ),
    ),
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=yii_account_test',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ),
    ),
);