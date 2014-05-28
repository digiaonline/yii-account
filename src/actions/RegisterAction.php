<?php

namespace nordsoftware\yii_account\actions;

class RegisterAction extends \CAction
{
    public function run()
    {
        $this->controller->render('login');
    }
} 