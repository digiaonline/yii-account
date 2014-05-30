<?php

namespace nordsoftware\yii_account\controllers;

class ForgotPasswordController extends AccountController
{
    public function actionIndex()
    {
        $this->render('index');
    }
} 