<?php

namespace nordsoftware\yii_account\controllers;

class AccountController extends \CController
{
    /**
     * @var string
     */
    public $modelClass = '\nordsoftware\yii_account\models\Account';

    /**
     * @var string
     */
    public $layout = 'application.views.layouts.main';

    /**
     * @var string
     */
    public $defaultAction = 'login';

    /**
     * @inheritDoc
     */
    public function actions()
    {
        return array(
            'register' => '\nordsoftware\yii_account\actions\RegisterAction',
            'login' => '\nordsoftware\yii_account\actions\LoginAction',
            'logout' => '\nordsoftware\yii_account\actions\LogoutAction',
        );
    }

    /**
     * @param $id
     * @return \nordsoftware\yii_account\models\Account
     */
    public function loadModel($id)
    {
        $model = \CActiveRecord::model($this->modelClass)->findByPk($id);
        if ($model === null) {
            throw new \CHttpException(404, \Yii::t('yii-account', "Account #$id not found."));
        }
        return $model;
    }
}