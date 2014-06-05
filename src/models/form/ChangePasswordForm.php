<?php
/**
 * ChangePasswordForm class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.form.models
 */

namespace nordsoftware\yii_account\models\form;

use nordsoftware\yii_account\helpers\Helper;

class ChangePasswordForm extends \CFormModel
{
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
            array('password, verifyPassword', 'required'),
            array('verifyPassword', 'compare', 'compareAttribute' => 'password'),
        );
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return array(
            'password' => Helper::t('labels', 'Password'),
            'verifyPassword' => Helper::t('labels', 'Verify Password'),
        );
    }
} 