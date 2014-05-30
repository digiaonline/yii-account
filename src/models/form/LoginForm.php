<?php

namespace nordsoftware\yii_account\models\form;

use nordsoftware\yii_account\AccountModule;

class LoginForm extends \CFormModel
{
    /**
     * @var
     */
    public $username;

    /**
     * @var
     */
    public $password;

    /**
     * @var
     */
    public $rememberMe;

    /**
     * @var \CUserIdentity
     */
    protected $identity;

    /**
     * @var \nordsoftware\yii_account\AccountModule
     */
    private $_module;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('email, password', 'ParsleyRequiredValidator'),
            array('email', 'ParsleyEmailValidator'),
            array('rememberMe', 'boolean'),
            array('password', 'authenticate'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'rememberMe' => t('userLogin', 'Remember me'),
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $module = $this->getModule();

            $this->identity = new $module->identityClass($this->email, $this->password);

            if (!$this->identity->authenticate()) {
                $this->addError($attribute, t('yii-account', 'Your username or password is invalid.'));
            }
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        /** @var \nordsoftware\yii_account\AccountModule $module */
        $module = \Yii::app()->getModule(AccountModule::MODULE_ID);

        if (!isset($this->_identity)) {
            $this->identity = new $module->identityClass($this->username, $this->password);
            $this->identity->authenticate();
        }

        if ($this->identity->errorCode !== \CUserIdentity::ERROR_NONE) {
            return false;
        }

        $duration = $this->rememberMe ? $module->loginExpireTime : 0; // 30 days
        \Yii::app()->user->login($this->_identity, $duration);

        return true;
    }

    /**
     * @return \nordsoftware\yii_account\AccountModule
     */
    public function getModule()
    {
        if (!isset($this->_module)) {
            $this->_module = \Yii::app()->getModule(AccountModule::MODULE_ID);
        }
        return $this->_module;
    }
}