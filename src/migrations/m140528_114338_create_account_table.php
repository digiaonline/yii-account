<?php

class m140528_114338_create_account_table extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable(
            'account',
            array(
                'id' => 'pk',
                'salt' => 'string NOT NULL',
                'username' => 'string NOT NULL',
                'password' => 'string NOT NULL',
                'passwordStrategy' => 'string NOT NULL',
                'requireNewPassword' => "boolean NOT NULL DEFAULT '0'",
                'admin' => "boolean NOT NULL DEFAULT '0'",
                'lastLoginAt' => 'timestamp NULL DEFAULT NULL',
                'lastActiveAt' => 'timestamp NULL DEFAULT NULL',
                'status' => "integer NOT NULL DEFAULT '0'",
            )
        );
    }

    public function safeDown()
    {
        $this->dropTable('account');
    }
}