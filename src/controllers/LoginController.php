<?php

namespace nordsoftware\yii_account\controllers;

use nordsoftware\yii_account\models\form\LoginForm;

class LoginController extends AccountController
{
    public function actionIndex()
    {
        if (!\Yii::app()->user->isGuest) {
            $this->redirect(\Yii::app()->homeUrl);
        }

        $model = new LoginForm;

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'loginForm') {
            echo \CActiveForm::validate($model);
            \Yii::app()->end();
        }

        if (isset($_POST['nordsoftware_yii_account_models_form_LoginForm'])) {
            $model->attributes = $_POST['nordsoftware_yii_account_models_form_LoginForm'];

            if ($model->validate() && $model->login())
                $this->redirect(\Yii::app()->user->returnUrl);
        }

        $this->render('index', array('model' => $model));
    }
} 