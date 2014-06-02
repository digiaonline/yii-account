<?php

namespace nordsoftware\yii_account\controllers;
use nordsoftware\yii_account\AccountModule;

/**
 * @property \nordsoftware\yii_account\AccountModule $module
 */
class AccountController extends \CController
{
    /**
     * @var string
     */
    public $layout = 'application.views.layouts.main';

    /**
     * Loads a specific account model.
     * @param int $id account identifier.
     * @throws \CHttpException if the account model cannot be found.
     * @return \nordsoftware\yii_account\models\ar\Account
     */
    public function loadModel($id)
    {
        $modelClass = $this->module->getClassName(AccountModule::CLASS_MODEL);
        $model = \CActiveRecord::model($modelClass)->findByPk($id);

        if ($model === null) {
            throw new \CHttpException(404, \Yii::t('yii-account', "Account #$id not found."));
        }

        return $model;
    }
}