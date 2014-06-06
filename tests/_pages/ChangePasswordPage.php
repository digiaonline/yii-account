<?php

class ChangePasswordPage
{
    static $URL = 'index.php?r=account/password/change';

    static $fieldPassword = '#nordsoftware_yii_account_models_form_PasswordForm_password';
    static $fieldVerifyPassword = '#nordsoftware_yii_account_models_form_PasswordForm_verifyPassword';

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