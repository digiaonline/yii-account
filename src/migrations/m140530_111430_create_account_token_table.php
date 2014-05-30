<?php

class m140530_111430_create_account_token_table extends CDbMigration
{
	public function safeUp()
	{
        $this->createTable(
            'account_token',
            array(
                'id' => 'pk',
                'accountId' => 'int NOT NULL',
                'type' => 'string NOT NULL',
                'token' => 'string NOT NULL',
                'expires' => 'string NOT NULL',
                'status' => "integer NOT NULL DEFAULT '0'",
            )
        );
	}

	public function safeDown()
	{
        $this->dropTable('account_token');
	}
}