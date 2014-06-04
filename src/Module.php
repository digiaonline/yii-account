<?php

namespace nordsoftware\yii_account;

use nordsoftware\yii_account\exceptions\Exception;
use nordsoftware\yii_account\models\ar\AccountToken;

class Module extends \CWebModule
{
    // Canonical identifier for this module.
    const MODULE_ID = 'account';

    // Class name types.
    const CLASS_MODEL = 'model';
    const CLASS_TOKEN_MODEL = 'tokenModel';
    const CLASS_USER_IDENTITY = 'userIdentity';
    const CLASS_LOGIN_FORM = 'loginForm';
    const CLASS_REGISTER_FORM = 'registerForm';
    const CLASS_FORGOT_PASSWORD_FORM = 'forgotPasswordForm';
    const CLASS_RESET_PASSWORD_FORM = 'resetPasswordForm';

    // Controller types.
    const CONTROLLER_AUTHENTICATE = 'authenticate';
    const CONTROLLER_PASSWORD = 'password';
    const CONTROLLER_REGISTER = 'register';

    // Token types.
    const TOKEN_ACTIVATE = 'activate';
    const TOKEN_RESET_PASSWORD = 'resetPassword';

    // Component identifiers.
    const COMPONENT_TOKEN_GENERATOR = 'tokenGenerator';

    /**
     * @var array
     */
    public $classMap = array();

    /**
     * @var array
     */
    public $views = array();

    /**
     * @var string
     */
    public $defaultController = 'login';

    /**
     * @var string
     */
    public $defaultLayout = 'narrow';

    /**
     * @var bool
     */
    public $enableActivation = true;

    /**
     * @var int
     */
    public $loginExpireTime = 2592000; // 30 days

    /**
     * @var int
     */
    public $activateExpireTime = 2592000; // 30 days

    /**
     * @var int
     */
    public $recoverExpireTime = 86400; // 1 day

    /**
     * @var string
     */
    public $fromEmailAddress;

    /**
     * @var string
     */
    public $messageSource = 'messages';

    /**
     * @var bool
     */
    public $registerStyles = true;

    /**
     * @var bool
     */
    public $forcePublishAssets = true;

    /**
     * @inheritDoc
     */
    protected function init()
    {
        if ($this->fromEmailAddress === null) {
            throw new Exception("Required property Module.fromEmailAddress not set.");
        }

        $this->classMap = array_merge(
            array(
                self::CLASS_MODEL => '\nordsoftware\yii_account\models\ar\Account',
                self::CLASS_TOKEN_MODEL => '\nordsoftware\yii_account\models\ar\AccountToken',
                self::CLASS_USER_IDENTITY => '\nordsoftware\yii_account\components\UserIdentity',
                self::CLASS_LOGIN_FORM => '\nordsoftware\yii_account\models\form\LoginForm',
                self::CLASS_REGISTER_FORM => '\nordsoftware\yii_account\models\form\RegisterForm',
                self::CLASS_FORGOT_PASSWORD_FORM => '\nordsoftware\yii_account\models\form\ForgotPasswordForm',
                self::CLASS_RESET_PASSWORD_FORM => '\nordsoftware\yii_account\models\form\ResetPasswordForm',
            ),
            $this->classMap
        );

        $this->setComponents(
            array(
                'tokenGenerator' => array(
                    'class' => 'nordsoftware\yii_account\components\TokenGenerator',
                ),
            )
        );

        \Yii::app() instanceof \CWebApplication ? $this->initWeb() : $this->initConsole();
    }

    /**
     * Initializes the module for the web application.
     */
    protected function initWeb()
    {
        $this->controllerMap = \CMap::mergeArray(
            array(
                self::CONTROLLER_PASSWORD => array(
                    'class' => 'nordsoftware\yii_account\controllers\PasswordController',
                ),
                self::CONTROLLER_AUTHENTICATE => array(
                    'class' => 'nordsoftware\yii_account\controllers\AuthenticateController',
                ),
                self::CONTROLLER_REGISTER => array(
                    'class' => 'nordsoftware\yii_account\controllers\RegisterController',
                ),
            ),
            $this->controllerMap
        );

        if ($this->registerStyles) {
            $assetsUrl = \Yii::app()->assetManager->publish(__DIR__ . '/assets', false, -1, $this->forcePublishAssets);
            \Yii::app()->clientScript->registerCssFile($assetsUrl . '/css/styles.css');
        }
    }

    /**
     * Initializes this application for the console application.
     */
    protected function initConsole()
    {
        // nothing here for now ...
    }

    /**
     * Sends an e-mail message.
     * Override this method in order to send mail properly (mail() should only be used during development).
     *
     * @param string $to recipient e-mail address.
     * @param string $subject mail subject.
     * @param string $body mail body.
     * @param array $config mail configurations.
     * @return bool whether or not the mail was sent successfully.
     */
    public function sendMail($to, $subject, $body, array $config = array())
    {
        $params = $config['params'];
        $headers = $config['headers'];
        $headers['from'] = $this->fromEmailAddress;

        return mail($to, $subject, $body, $headers, $params);
    }

    /**
     * Generates a new random token and saves it in the database.
     *
     * @param string $type token type.
     * @param int $accountId account id.
     * @param string $expires token expiration date (mysql date).
     * @throws \nordsoftware\yii_account\exceptions\Exception if the token cannot be generated.
     * @return string the generated token.
     */
    public function generateToken($type, $accountId, $expires)
    {
        if (!$this->hasComponent(Module::COMPONENT_TOKEN_GENERATOR)) {
            throw new Exception('Failed to get the token generator component.');
        }

        /** @var \nordsoftware\yii_account\components\TokenGenerator $tokenGenerator */
        $tokenGenerator = $this->getComponent(Module::COMPONENT_TOKEN_GENERATOR);
        $token = $tokenGenerator->generate();

        $modelClass = $this->getClassName(Module::CLASS_TOKEN_MODEL);

        /** @var \nordsoftware\yii_account\models\ar\AccountToken $model */
        $model = new $modelClass();
        $model->type = $type;
        $model->accountId = $accountId;
        $model->token = $token;
        $model->expiresAt = $expires;

        if (!$model->save()) {
            throw new Exception('Failed to save token.');
        }

        return $token;
    }

    /**
     * Loads a token of a specific type.
     *
     * @param string $type token type.
     * @param string $token token string.
     * @throws \nordsoftware\yii_account\exceptions\Exception
     * @return \nordsoftware\yii_account\models\ar\AccountToken
     */
    public function loadToken($type, $token)
    {
        $modelClass = $this->getClassName(Module::CLASS_TOKEN_MODEL);

        /** @var \nordsoftware\yii_account\models\ar\AccountToken $model */
        $model = \CActiveRecord::model($modelClass)->findByAttributes(
            array('type' => $type, 'token' => $token, 'status' => AccountToken::STATUS_UNUSED)
        );

        if ($model === null || $model->hasExpired()) {
            return null;
        }

        return $model;
    }

    /**
     * Returns the class name for a specific class type.
     *
     * @param string $type
     * @throws \nordsoftware\yii_account\exceptions\Exception
     * @return string
     */
    public function getClassName($type)
    {
        if (!isset($this->classMap)) {
            throw new Exception("Trying to get class name for unknown class '$type'.");
        }

        return $this->classMap[$type];
    }
}