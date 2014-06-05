<?php

namespace nordsoftware\yii_account\models\ar;

use nordsoftware\yii_account\exceptions\Exception;
use nordsoftware\yii_account\helpers\Helper;
use nordsoftware\yii_account\Module;

/**
 * This is the model class for table account_login_history".
 *
 * The followings are the available columns in table 'account_login_history':
 *
 * @property integer $id
 * @property integer $accountId
 * @property integer $success
 * @property integer $numFailedAttempts
 * @property string $createdAt
 */
class AccountLoginHistory extends \CActiveRecord
{
    /**
     * @return string the associated database table name.
     */
    public function tableName()
    {
        return 'account_login_history';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('success', 'required'),
            array('accountId, numFailedAttempts', 'numerical', 'integerOnly' => true),
            array('accountId', 'numerical', 'allowEmpty' => true),
            array('success', 'boolean'),
        );
    }

    public static function createEntry($accountId, $success)
    {
        $modelClass = Helper::getModule()->getClassName(Module::CLASS_LOGIN_HISTORY);

        /** @var \nordsoftware\yii_account\models\ar\AccountLoginHistory $model */
        $model = new $modelClass();
        $model->accountId = $accountId;
        $model->success = $success;
        $model->numFailedAttempts = !$success ? $model->resolveNumFailedAttempts() : 0;

        if (!$model->save()) {
            throw new Exception('Failed to save login history entry.');
        }
    }

    public function resolveNumFailedAttempts()
    {
        $criteria = new \CDbCriteria();
        $criteria->addCondition('accountId=:accountId');
        $criteria->params[':accountId'] = $this->accountId;
        $criteria->order = 'createdAt DESC';

        /** @var \nordsoftware\yii_account\models\ar\AccountLoginHistory $lastEntry */
        $lastEntry = $this->find($criteria);
        $numFailedAttempts = $lastEntry !== null ? $lastEntry->numFailedAttempts : 0;

        return $numFailedAttempts + 1;
    }

    /**
     * Returns the static model of this class.
     * @param string $className active record class name.
     * @return AccountLoginHistory the static model class.
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
