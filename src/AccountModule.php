<?php

namespace nordsoftware\yii_account;

class AccountModule extends \CWebModule
{
    const MODULE_ID = 'account';

    /**
     * @var string
     */
    public $modelClass = '\nordsoftware\yii_account\models\ar\Account';

    /**
     * @var string
     */
    public $identityClass = '\nordsoftware\yii_account\components\UserIdentity';

    /**
     * @var string
     */
    public $defaultController = 'login';

    /**
     * @var int
     */
    public $loginExpireTime = 2592000; // 30 days

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
                'activate' => 'nordsoftware\yii_account\controllers\ActivateController',
                'forgot' => 'nordsoftware\yii_account\controllers\ForgotController',
                'login' => 'nordsoftware\yii_account\controllers\LoginController',
                'logout' => 'nordsoftware\yii_account\controllers\LogoutController',
                'register' => 'nordsoftware\yii_account\controllers\RegisterController',
            ),
            $this->controllerMap
        );

        $assetsUrl = \Yii::app()->assetManager->publish(__DIR__ . '/assets', false, -1, $this->forcePublishAssets);
        \Yii::app()->clientScript->registerCssFile($assetsUrl . '/css/styles.css');
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
     * @param string $from sender e-mail address.
     * @param string $to recipient e-mail address.
     * @param string $subject mail subject.
     * @param string $body mail body.
     * @param array $config mail configurations.
     * @return bool whether or not the mail was sent successfully.
     */
    public function sendMail($from, $to, $subject, $body, array $config = array())
    {
        $params = $config['params'];
        $headers = $config['headers'];

        $headers['from'] = $from;

        return mail($to, $subject, $body, $headers, $params);
    }

    /**
     * Generates a new random token.
     *
     * @return string the generated token.
     */
    public function generateToken()
    {
        /** @var \nordsoftware\yii_account\components\TokenGenerator $tokenGenerator */
        $tokenGenerator = $this->getComponent('tokenGenerator');
        return $tokenGenerator->generate();
    }
}