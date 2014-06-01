<?php

namespace nordsoftware\yii_account\controllers;

class ForgotController extends AccountController
{
    public function actionIndex()
    {
        $this->render('index');
    }
} 