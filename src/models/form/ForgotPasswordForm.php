<?php
/**
 * ForgotPasswordForm class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.form.models
 */

namespace nordsoftware\yii_account\models\form;

use nordsoftware\yii_account\helpers\Helper;
use nordsoftware\yii_account\Module;

class ForgotPasswordForm extends \CFormModel
{
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
            array('email', 'required'),
            array('email', 'email'),
            array('email', 'validateAccountExists'),
        );
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return array(
            'email' => Helper::t('labels', 'Email'),
        );
    }

    public function validateAccountExists($attribute, $params)
    {
        if (($model = $this->loadModel($this->email)) === null) {
            $this->addError($attribute, Helper::t('errors', 'Account not found.'));
        }
    }

    /**
     * @param string $email
     * @return \nordsoftware\yii_account\models\ar\Account
     * @throws \nordsoftware\yii_account\exceptions\Exception
     */
    public function loadModel($email)
    {
        $modelClass = Helper::getModule()->getClassName(Module::CLASS_MODEL);

        return \CActiveRecord::model($modelClass)->findByAttributes(array('email' => $email));
    }
}