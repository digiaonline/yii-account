<?php

$basePath = dirname(__DIR__);

return array(
    'name' => 'Application',
    'basePath' => $basePath,
    'runtimePath' => "$basePath/runtime",
    'aliases' => array(
        'yii-account' => realpath("$basePath/../../src"),
        'vendor' => realpath("$basePath/../../vendor"),
    ),
    'modules' => array(
        'account' => array(
            'class' => 'application.modules.account.AccountModule',
        ),
    ),
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=yii_account',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ),
    ),
);