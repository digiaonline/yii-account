<?php

namespace nordsoftware\yii_account\components;

use nordsoftware\yii_account\AccountModule;
use nordsoftware\yii_account\exceptions\AccountException;
use nordsoftware\yii_account\helpers\Helper;

class WebUser extends \CWebUser
{
    /**
     * @var \nordsoftware\yii_account\models\ar\Account
     */
    private $_model;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        if (!$this->isGuest) {
            $this->updateLastActiveAt();
        }
    }

    /**
     * Loads the user model for the logged in user.
     * @throws \nordsoftware\yii_account\exceptions\AccountException if the user is a guest.
     * @return \nordsoftware\yii_account\models\ar\Account the model.
     */
    public function loadModel()
    {
        if ($this->isGuest) {
            throw new AccountException("Trying to load model for guest user.");
        }

        if (!isset($this->_model)) {
            $modelClass = $this->module->getClassName(AccountModule::CLASS_MODEL);
            $this->_model = \CActiveRecord::model($modelClass)->findByPk($this->id);
        }

        return $this->_model;
    }

    /**
     * Updates the users last active at field.
     * @throws \nordsoftware\yii_account\exceptions\AccountException if saving the model cannot be saved.
     * @return boolean whether the update was successful.
     */
    public function updateLastActiveAt()
    {
        $model = $this->loadModel();
        $model->lastActiveAt = Helper::sqlDateTime();

        if (!$model->save(true, array('lastActiveAt'))) {
            throw new AccountException("Failed to update lastActiveAt for account #{$this->id}.");
        }

        return true;
    }

    /**
     * Updates the users last login at field.
     * @throws \nordsoftware\yii_account\exceptions\AccountException if saving the model cannot be saved.
     * @return boolean whether the update was successful.
     */
    public function updateLastLoginAt()
    {
        $model = $this->loadModel();
        $model->lastLoginAt = Helper::sqlDateTime();

        if (!$model->save(true, array('lastLoginAt'))) {
            throw new AccountException("Failed to update lastLoginAt for account #{$this->id}.");
        }

        return true;
    }

    /**
     * @return \nordsoftware\yii_account\AccountModule
     */
    protected function getModule()
    {
        return \Yii::app()->getModule(AccountModule::MODULE_ID);
    }
} 