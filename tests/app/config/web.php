<?php
return \CMap::mergeArray(
    require('main.php'),
    array(
        'components' => array(
            'yiistrap' => array(
                'class' => '\TbApi',
            ),
        ),
    )
);