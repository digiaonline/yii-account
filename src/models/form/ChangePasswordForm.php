<?php

namespace nordsoftware\yii_account\models\form;

use nordsoftware\yii_account\helpers\Helper;

class ChangePasswordForm extends \CFormModel
{
    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $verifyPassword;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array(
            array('password, verifyPassword', 'required'),
            array('verifyPassword', 'compare', 'compareAttribute' => 'password'),
        );
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return array(
            'password' => Helper::t('labels', 'Password'),
            'verifyPassword' => Helper::t('labels', 'Verify Password'),
        );
    }
} 