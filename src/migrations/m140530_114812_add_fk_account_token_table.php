<?php

class m140530_114812_add_fk_account_token_table extends CDbMigration
{
	public function safeUp()
	{
        $this->addForeignKey('account_token_accountId', 'account_token', 'accountId', 'account', 'id');
	}

	public function safeDown()
	{
        $this->dropForeignKey('account_token_accountId', 'account_token');
	}
}