<?php
return \CMap::mergeArray(
    require('main.php'),
    array(
        'theme' => 'fancy',
        'components' => array(
            'user' => array(
                'class' => '\nordsoftware\yii_account\components\WebUser',
            ),
            'themeManager' => array(
                'basePath' => dirname(__DIR__) . '/themes',
            ),
            'yiistrap' => array(
                'class' => '\TbApi',
            ),
        ),
    )
);