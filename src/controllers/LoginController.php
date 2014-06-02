<?php

namespace nordsoftware\yii_account\controllers;

use nordsoftware\yii_account\AccountModule;
use nordsoftware\yii_account\helpers\Helper;

class LoginController extends AccountController
{
    /**
     * Displays the 'login' page.
     */
    public function actionIndex()
    {
        if (!\Yii::app()->user->isGuest) {
            $this->redirect(\Yii::app()->homeUrl);
        }

        $loginFormClass = $this->module->getClassName(AccountModule::CLASS_LOGIN_FORM);

        /** @var \nordsoftware\yii_account\models\form\LoginForm $model */
        $model = new $loginFormClass();

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'loginForm') {
            echo \CActiveForm::validate($model);
            \Yii::app()->end();
        }

        $request = \Yii::app()->request;

        if ($request->isPostRequest) {
            $model->attributes = $request->getPost(Helper::classNameToKey($loginFormClass));

            if ($model->validate() && $model->login()) {
                \Yii::app()->user->updateLastLoginAt();
                $this->redirect(\Yii::app()->user->returnUrl);
            }
        }

        $this->render('index', array('model' => $model));
    }
} 