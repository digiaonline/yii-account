<?php

namespace nordsoftware\yii_account\models\form;

use nordsoftware\yii_account\Module;
use nordsoftware\yii_account\helpers\Helper;

class LoginForm extends \CFormModel
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
     * @var boolean
     */
    public $stayLoggedIn;

    /**
     * @var \CUserIdentity
     */
    private $_identity;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array(
            array('username, password', 'required'),
            array('stayLoggedIn', 'boolean'),
            array('password', 'authenticate'),
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
            'stayLoggedIn' => Helper::t('labels', 'Stay logged in'),
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $identityClass = Helper::getModule()->getClassName(Module::CLASS_USER_IDENTITY);

            $this->_identity = new $identityClass($this->username, $this->password);

            if (!$this->_identity->authenticate()) {
                $this->addError('password', Helper::t('errors', 'Your username or password is invalid.'));
            }
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        /** @var \nordsoftware\yii_account\Module $module */
        $module = \Yii::app()->getModule(Module::MODULE_ID);

        if (!isset($this->_identity)) {
            $this->_identity = new $module->identityClass($this->username, $this->password);
            $this->_identity->authenticate();
        }

        if ($this->_identity->errorCode !== \CUserIdentity::ERROR_NONE) {
            return false;
        }

        $duration = $this->stayLoggedIn ? $module->loginExpireTime : 0; // 30 days
        \Yii::app()->user->login($this->_identity, $duration);

        return true;
    }
}