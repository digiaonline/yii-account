<?php

class LoginPage
{
    static $URL = '?r=account/authenticate/login';

    static $fieldUsername = '#nordsoftware_yii_account_models_form_LoginForm_username';
    static $fieldPassword = '#nordsoftware_yii_account_models_form_LoginForm_password';

    static $buttonSubmit = '#loginForm button[type=submit]';

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: EditPage::route('/123-post');
     */
     public static function route($param)
     {
        return static::$URL.$param;
     }
}