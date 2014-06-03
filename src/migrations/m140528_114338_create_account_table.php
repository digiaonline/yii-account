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
                'email' => 'string NOT NULL',
                'passwordStrategy' => 'string NOT NULL',
                'requireNewPassword' => "boolean NOT NULL DEFAULT '0'",
                'lastLoginAt' => 'datetime NULL DEFAULT NULL',
                'lastActiveAt' => 'datetime NULL DEFAULT NULL',
                'status' => "integer NOT NULL DEFAULT '0'",
                'UNIQUE KEY username (username)',
                'UNIQUE KEY email (email)',
            )
        );
    }

    public function safeDown()
    {
        $this->dropTable('account');
    }
}