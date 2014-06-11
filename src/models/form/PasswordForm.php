<?php
/**
 * PasswordForm class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.form.models
 */

namespace nordsoftware\yii_account\models\form;

use nordsoftware\yii_account\exceptions\Exception;
use nordsoftware\yii_account\helpers\Helper;
use nordsoftware\yii_account\models\ar\Account;
use nordsoftware\yii_account\Module;

class PasswordForm extends \CFormModel
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

    /**
     * Returns whether the password has been used in the past.
     *
     * @param \CActiveRecord|\nordsoftware\yii_account\models\ar\Account $account account model.
     * @param string $password plain text password.
     * @return bool whether the password has been used in the past.
     */
    public function checkPasswordHistory(\CActiveRecord $account, $password)
    {
        $criteria = new \CDbCriteria();
        $criteria->addCondition('accountId=:accountId');
        $criteria->params[':accountId'] = $account->id;
        $criteria->order = 'createdAt DESC';
        $criteria->limit = 10; // 10 should be enough

        $modelClass = Helper::getModule()->getClassName(Module::CLASS_PASSWORD_HISTORY);

        /** @var \nordsoftware\yii_account\models\ar\AccountPasswordHistory[] $models */
        $models = \CActiveRecord::model($modelClass)->findAll($criteria);

        $strategy = $account->getStrategy();

        foreach ($models as $model) {
            $strategy->setSalt($model->salt);
            if ($model->password === $strategy->encode($password)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Creates a password history entry.
     *
     * @param int $accountId account id.
     * @param string $salt password salt.
     * @param string $password hashed password.
     * @throws \nordsoftware\yii_account\exceptions\Exception if the history entry cannot be saved.
     */
    public function createHistoryEntry($accountId, $salt, $password)
    {
        $modelClass = Helper::getModule()->getClassName(Module::CLASS_PASSWORD_HISTORY);

        /** @var \nordsoftware\yii_account\models\ar\AccountPasswordHistory $model */
        $model = new $modelClass();
        $model->accountId = $accountId;
        $model->salt = $salt;
        $model->password = $password;

        if (!$model->save()) {
            throw new Exception('Failed to save password history entry.');
        }
    }
} 