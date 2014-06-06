<?php

return \CMap::mergeArray(
    require('main.php'),
    array(
        'commandMap' => array(
            'account' => array(
                'class' => '\nordsoftware\yii_account\commands\AccountCommand',
            ),
            'generate' => array(
                'class' => '\crisu83\yii_caviar\commands\GenerateCommand',
                'basePath' => dirname(__DIR__) . '/../../src',
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
            'message' => array(
                'class' => 'system.cli.commands.MessageCommand',
            ),
        ),
    )
);