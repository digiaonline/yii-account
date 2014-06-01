<?php
return \CMap::mergeArray(
    require('main.php'),
    array(
        'components' => array(
            'user' => array(
                'class' => '\nordsoftware\yii_account\components\WebUser',
            ),
            'yiistrap' => array(
                'class' => '\TbApi',
            ),
        ),
    )
);