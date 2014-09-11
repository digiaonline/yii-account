<?php
/**
 * Module class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account
 */

namespace nordsoftware\yii_account;

use nordsoftware\yii_account\exceptions\Exception;
use nordsoftware\yii_account\helpers\Helper;
use nordsoftware\yii_account\models\ar\AccountToken;

class Module extends \CWebModule
{
    // Canonical identifier for this module.
    const MODULE_ID = 'account';

    // Class name types.
    const CLASS_ACCOUNT = 'account';
    const CLASS_TOKEN = 'token';
    const CLASS_USER_IDENTITY = 'userIdentity';
    const CLASS_LOGIN_FORM = 'loginForm';
    const CLASS_PASSWORD_FORM = 'passwordForm';
    const CLASS_SIGNUP_FORM = 'signupForm';
    const CLASS_FORGOT_PASSWORD_FORM = 'forgotPasswordForm';
    const CLASS_LOGIN_HISTORY = 'loginHistory';
    const CLASS_PASSWORD_HISTORY = 'passwordHistory';
    const CLASS_CAPTCHA_ACTION = 'captchaAction';
    const CLASS_CAPTCHA_WIDGET = 'captchaWidget';

    // Controller types.
    const CONTROLLER_AUTHENTICATE = 'authenticate';
    const CONTROLLER_PASSWORD = 'password';
    const CONTROLLER_SIGNUP = 'signup';

    // Token types.
    const TOKEN_ACTIVATE = 'activate';
    const TOKEN_RESET_PASSWORD = 'resetPassword';
    const TOKEN_CHANGE_PASSWORD = 'changePassword';

    // Component identifiers.
    const COMPONENT_TOKEN_GENERATOR = 'tokenGenerator';

    /**
     * @var array map over classes to use by the module.
     */
    public $classMap = array();

    /**
     * @var bool whether to enable activation (defaults to true).
     */
    public $enableActivation = true;

    /**
     * @var bool whether to enable captcha on sign up (defaults to false).
     */
    public $enableCaptcha = false;

    /**
     * @var int number of times a user can fail to login before the account is locked (defaults to 10).
     */
    public $numAllowedFailedLogins = 10;

    /**
     * @var int number of seconds for login to expire.
     */
    public $loginExpireTime = 2592000; // 30 days

    /**
     * @var int number of seconds for account activation to expire.
     */
    public $activateExpireTime = 2592000; // 30 days

    /**
     * @var int number of seconds for password reset to expire.
     */
    public $resetPasswordExpireTime = 86400; // 1 day

    /**
     * @var int number of seconds for passwords to expire (defaults to disabled).
     */
    public $passwordExpireTime = 0; // disabled

    /**
     * @var int number of seconds for login lockout to expire (defaults to disabled).
     */
    public $lockoutExpireTime = 600; // 10 minutes

    /**
     * @var string from e-mail address.
     */
    public $fromEmailAddress;

    /**
     * @var string message source to use for this module.
     */
    public $messageSource = 'messages';

    /**
     * @var bool whether to register styles.
     */
    public $registerStyles = true;

    /**
     * @var string default controller.
     */
    public $defaultController = 'login';

    /**
     * @var string default layout.
     */
    public $defaultLayout = 'narrow';

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
                self::CLASS_ACCOUNT => '\nordsoftware\yii_account\models\ar\Account',
                self::CLASS_TOKEN => '\nordsoftware\yii_account\models\ar\AccountToken',
                self::CLASS_LOGIN_HISTORY => '\nordsoftware\yii_account\models\ar\AccountLoginHistory',
                self::CLASS_PASSWORD_HISTORY => '\nordsoftware\yii_account\models\ar\AccountPasswordHistory',
                self::CLASS_USER_IDENTITY => '\nordsoftware\yii_account\components\UserIdentity',
                self::CLASS_LOGIN_FORM => '\nordsoftware\yii_account\models\form\LoginForm',
                self::CLASS_PASSWORD_FORM => '\nordsoftware\yii_account\models\form\PasswordForm',
                self::CLASS_SIGNUP_FORM => '\nordsoftware\yii_account\models\form\SignupForm',
                self::CLASS_FORGOT_PASSWORD_FORM => '\nordsoftware\yii_account\models\form\ForgotPasswordForm',
                self::CLASS_CAPTCHA_ACTION => '\CCaptchaAction',
                self::CLASS_CAPTCHA_WIDGET => '\CCaptcha',
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
                self::CONTROLLER_SIGNUP => array(
                    'class' => 'nordsoftware\yii_account\controllers\SignupController',
                ),
            ),
            $this->controllerMap
        );

        if ($this->registerStyles) {
            $assetsUrl = \Yii::app()->assetManager->publish(__DIR__ . '/assets', false, -1);
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
        $params = isset($config['params']) ? $config['params'] : array();
        $headers = isset($config['headers']) ? $config['headers'] : array();

        $headers['from'] = $this->fromEmailAddress;

        return mail($to, $subject, $body, $headers, $params);
    }

    /**
     * Generates a new random token and saves it in the database.
     *
     * @param string $type token type.
     * @param int $accountId account id.
     * @throws \nordsoftware\yii_account\exceptions\Exception if the token cannot be generated.
     * @return string the generated token.
     */
    public function generateToken($type, $accountId)
    {
        if (!$this->hasComponent(Module::COMPONENT_TOKEN_GENERATOR)) {
            throw new Exception('Failed to get the token generator component.');
        }

        /** @var \nordsoftware\yii_account\components\TokenGenerator $tokenGenerator */
        $tokenGenerator = $this->getComponent(Module::COMPONENT_TOKEN_GENERATOR);
        $token = $tokenGenerator->generate();

        $modelClass = $this->getClassName(Module::CLASS_TOKEN);

        /** @var \nordsoftware\yii_account\models\ar\AccountToken $model */
        $model = new $modelClass();
        $model->type = $type;
        $model->accountId = $accountId;
        $model->token = $token;

        if (!$model->save()) {
            throw new Exception('Failed to save authentication token.');
        }

        return $token;
    }

    /**
     * Loads a token of a specific type.
     *
     * @param string $type token type.
     * @param string $token token string.
     * @return \nordsoftware\yii_account\models\ar\AccountToken authentication token model.
     */
    public function loadToken($type, $token)
    {
        $modelClass = $this->getClassName(Module::CLASS_TOKEN);

        /** @var \nordsoftware\yii_account\models\ar\AccountToken $model */
        $model = \CActiveRecord::model($modelClass)->findByAttributes(
            array('type' => $type, 'token' => $token, 'status' => AccountToken::STATUS_UNUSED)
        );

        return $model;
    }

    /**
     * Returns whether the given token has expired.
     *
     * @param \CActiveRecord|\nordsoftware\yii_account\models\ar\AccountToken $tokenModel authentication token model.
     * @param int $expireTime number of seconds that the token is valid.
     * @return bool whether the token has expired.
     */
    public function hasTokenExpired(\CActiveRecord $tokenModel, $expireTime)
    {
        return strtotime(Helper::sqlNow()) - strtotime($tokenModel->createdAt) > $expireTime;
    }

    /**
     * Returns the class name for a specific class type.
     *
     * @param string $type class type.
     * @throws \nordsoftware\yii_account\exceptions\Exception if the class cannot be found.
     * @return string class name.
     */
    public function getClassName($type)
    {
        if (!isset($this->classMap)) {
            throw new Exception("Trying to get class name for unknown class '$type'.");
        }

        return $this->classMap[$type];
    }
}