<?php

namespace nordsoftware\yii_account\models\form;

use nordsoftware\yii_account\Module;
use nordsoftware\yii_account\helpers\Helper;

class SignupForm extends \CFormModel
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $verifyPassword;

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
            array('email, username, password, verifyPassword', 'required'),
            array('email', 'email'),
            array('username, email', 'unique', 'className' => '\nordsoftware\yii_account\models\ar\Account'),
            array('password', 'length', 'min' => 6),
            array('verifyPassword', 'compare', 'compareAttribute' => 'password'),
        );
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return array(
            'username' => Helper::t('labels', 'Username'),
            'password' => Helper::t('labels', 'Password'),
            'verifyPassword' => Helper::t('labels', 'Verify Password'),
            'email' => Helper::t('labels', 'Email'),
        );
    }
}