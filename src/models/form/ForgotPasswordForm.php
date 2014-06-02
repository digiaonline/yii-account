<?php

namespace nordsoftware\yii_account\models\form;

use nordsoftware\yii_account\helpers\Helper;

class ForgotPasswordForm extends \CFormModel
{
    /**
     * @var string
     */
    public $email;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array(
            array('email', 'required'),
            array('email', 'email'),
        );
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return array(
            'email' => Helper::t('labels', 'Email'),
        );
    }
}