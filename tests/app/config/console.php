<?php

return \CMap::mergeArray(
    require('main.php'),
    array(
        'commandMap' => array(
            'account' => array(
                'class' => '\nordsoftware\yii_account\commands\AccountCommand',
            ),
            'migrate' => array(
                'class' => 'system.cli.commands.MigrateCommand',
                'migrationPath' => 'yii-account.migrations',
                'migrationTable' => 'migration',
            ),
            'mysqldump' => array(
                'class' => 'vendor.crisu83.yii-consoletools.commands.MysqldumpCommand',
                'dumpPath' => '../_data',
            ),
        ),
    )
);