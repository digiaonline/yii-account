<?php
/**
 * SignupForm class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.form.models
 */

namespace nordsoftware\yii_account\models\form;

use nordsoftware\yii_account\helpers\Helper;

class SignupForm extends PasswordForm
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $captcha;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            array(
                array('email, username', 'required'),
                array('username', 'length', 'min' => 4),
                array('email', 'email'),
                array('username, email', 'unique', 'className' => '\nordsoftware\yii_account\models\ar\Account'),
                array('captcha', 'required', 'on' => 'withCaptcha'),
                array('captcha', 'captcha', 'skipOnError' => true, 'on' => 'withCaptcha'),
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            array(
                'email' => Helper::t('labels', 'Email'),
                'username' => Helper::t('labels', 'Username'),
                'captcha' => Helper::t('labels', 'Captcha'),
            )
        );
    }
}