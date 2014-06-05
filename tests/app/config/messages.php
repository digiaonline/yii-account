<?php

return array(
    'sourcePath' => dirname(__DIR__) . '/../../src',
    'messagePath' => dirname(__DIR__) . '/../../src/messages',
    'languages' => array('templates'),
    'fileTypes' => array('php'),
    'exclude' => array(
        '/messages',
        '/migrations',
        'yiic.php',
    ),
    'translator' => 'Helper::t',
    'overwrite' => true,
    'removeOld' => true,
    'sort' => true,
);