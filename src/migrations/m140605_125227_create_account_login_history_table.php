<?php

class m140605_125227_create_account_login_history_table extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable(
            'account_login_history',
            array(
                'id' => 'pk',
                'accountId' => "int NOT NULL DEFAULT '0'",
                'success' => "boolean NOT NULL DEFAULT '0'",
                'numFailedAttempts' => "int NOT NULL DEFAULT '0'",
                'createdAt' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            )
        );
    }

    public function safeDown()
    {
        $this->dropTable('account_login_history');
    }
}