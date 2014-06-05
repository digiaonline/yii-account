<?php
/**
 * SignupForm class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.form.models
 */

namespace nordsoftware\yii_account\models\form;

use nordsoftware\yii_account\Module;
use nordsoftware\yii_account\helpers\Helper;

class SignupForm extends \CFormModel
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
            array('email, username, password, verifyPassword', 'required'),
            array('username', 'length', 'min' => 4),
            array('email', 'email'),
            array('username, email', 'unique', 'className' => '\nordsoftware\yii_account\models\ar\Account'),
            array('verifyPassword', 'compare', 'compareAttribute' => 'password'),
        );
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return array(
            'email' => Helper::t('labels', 'Email'),
            'username' => Helper::t('labels', 'Username'),
            'password' => Helper::t('labels', 'Password'),
            'verifyPassword' => Helper::t('labels', 'Verify Password'),
        );
    }
}