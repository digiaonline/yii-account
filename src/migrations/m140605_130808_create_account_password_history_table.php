<?php

class m140605_130808_create_account_password_history_table extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable(
            'account_password_history',
            array(
                'id' => 'pk',
                'accountId' => 'int NOT NULL',
                'salt' => 'string NOT NULL',
                'password' => 'string NOT NULL',
                'createdAt' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            )
        );
    }

    public function safeDown()
    {
        $this->dropTable('account_password_history');
    }
}