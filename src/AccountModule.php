<?php

namespace nordsoftware\yii_account;

class AccountModule extends \CWebModule
{
    const MODULE_ID = 'account';

    /**
     * @var string
     */
    public $modelClass = '\nordsoftware\yii_account\models\Account';

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

    protected $identity;

    /**
     * @inheritDoc
     */
    protected function init()
    {
        $this->components = \CMap::mergeArray(
            array(
                'tokenGenerator' => array(
                    'class' => 'nordsoftware\yii_account\components\TokenGenerator',
                ),
            ),
            $this->components
        );

        $this->controllerMap = \CMap::mergeArray(
            array(
                'activate' => 'nordsoftware\yii_account\controllers\ActivateController',
                'forgotPassword' => 'nordsoftware\yii_account\controllers\ForgotPasswordController',
                'login' => 'nordsoftware\yii_account\controllers\LoginController',
                'logout' => 'nordsoftware\yii_account\controllers\LogoutController',
                'register' => 'nordsoftware\yii_account\controllers\RegisterController',
            ),
            $this->controllerMap
        );
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