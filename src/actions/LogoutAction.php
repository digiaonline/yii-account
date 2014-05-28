<?php

namespace nordsoftware\yii_account\controllers\account;

class LogoutAction extends \CAction
{
    public function run()
    {
        $this->controller->render('logout');
    }
} 