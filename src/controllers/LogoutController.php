<?php

namespace nordsoftware\yii_account\controllers;

class LogoutController extends AccountController
{
    public function actionIndex()
    {
        \Yii::app()->user->logout();
        $this->redirect(\Yii::app()->homeUrl);
    }
} 