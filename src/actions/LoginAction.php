<?php

namespace nordsoftware\yii_account\actions;

class LoginAction extends \CAction
{
    public function run()
    {
        $this->controller->render('login');
    }
} 