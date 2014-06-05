<?php

class ForgotPasswordPage
{
    static $URL = 'index.php?r=account/password/forgot';

    static $fieldEmail = '#nordsoftware_yii_account_models_form_ForgotPasswordForm_email';

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