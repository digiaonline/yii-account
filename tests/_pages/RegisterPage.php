<?php

class RegisterPage
{
    static $URL = 'index.php?r=account/register';

    static $fieldEmail = '#nordsoftware_yii_account_models_form_RegisterForm_email';
    static $fieldUsername = '#nordsoftware_yii_account_models_form_RegisterForm_username';
    static $fieldPassword = '#nordsoftware_yii_account_models_form_RegisterForm_password';
    static $fieldVerifyPassword = '#nordsoftware_yii_account_models_form_RegisterForm_verifyPassword';

    static $buttonSubmit = '#registerForm button[type=submit]';

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