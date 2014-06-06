<?php

class m140605_130815_add_fk_account_password_history_table extends CDbMigration
{
    public function safeUp()
    {
        $this->addForeignKey('account_password_history_accountId', 'account_password_history', 'accountId', 'account', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('account_password_history_accountId', 'account_password_history');
    }
}