<?php

namespace nordsoftware\yii_account\controllers;

class LoginController extends AccountController
{
    public function actionIndex()
    {
        $this->render('index');
    }
} 