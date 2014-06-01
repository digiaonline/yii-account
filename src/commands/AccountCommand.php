<?php

namespace nordsoftware\yii_account\commands;

use nordsoftware\yii_account\AccountModule;
use nordsoftware\yii_account\exceptions\AccountException;

class AccountCommand extends \CConsoleCommand
{
    public $defaultAction = 'create';

    /**
     * Creates a new account with the given username and password.
     *
     * @param string $username
     * @param string $password
     */
    public function actionCreate($username, $password)
    {
        $module = $this->getModule();

        /** @var \nordsoftware\yii_account\models\ar\Account $account */
        $account = new $module->modelClass();
        $account->username = $username;
        $account->password = $password;

        if (!$account->save(false)) {
            throw new AccountException("Failed to create account.");
        }

        $account->changePassword($password);

        echo "Account $username:$password created.\n";
    }

    /**
     * @return \nordsoftware\yii_account\AccountModule
     */
    public function getModule()
    {
        return \Yii::app()->getModule(AccountModule::MODULE_ID);
    }
} 