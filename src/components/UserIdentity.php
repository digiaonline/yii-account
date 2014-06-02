<?php

namespace nordsoftware\yii_account\components;

use nordsoftware\yii_account\AccountModule;

class UserIdentity extends \CUserIdentity
{
    /**
     * @var integer
     */
    private $_id;

    /**
     * @inheritDoc
     */
    public function authenticate()
    {
        $account = $this->loadModel();

        if ($account === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if (!$account->verifyPassword($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->errorCode = self::ERROR_NONE;
        }

        // No errors occurred, set user identity.
        if ($this->errorCode === self::ERROR_NONE) {
            $this->_id = $account->id;
            $this->username = $account->username;
        }

        return !$this->errorCode;
    }

    /**
     * @return \nordsoftware\yii_account\models\ar\Account|\YiiPassword\Behavior
     */
    protected function loadModel()
    {
        $modelClass = $this->module->getClassName(AccountModule::CLASS_MODEL);

        return \CActiveRecord::model($modelClass)->find(
            array(
                'condition' => 'username=:username',
                'params' => array(':username' => strtolower($this->username)),
            )
        );
    }

    /**
     * @return \nordsoftware\yii_account\AccountModule
     */
    protected function getModule()
    {
        return \Yii::app()->getModule(AccountModule::MODULE_ID);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }
}