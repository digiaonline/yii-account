<?php

namespace nordsoftware\yii_account\models\ar;

use nordsoftware\yii_account\helpers\Helper;

/**
 * This is the model class for table account_password_history".
 *
 * The followings are the available columns in table 'account_password_history':
 *
 * @property integer $id
 * @property integer $accountId
 * @property string $salt
 * @property string $password
 * @property string $createdAt
 *
 * The followings are the available model relations:
 *
 * @property \nordsoftware\yii_account\models\ar\Account $account
 */
class AccountPasswordHistory extends \CActiveRecord
{
    /**
     * @return string the associated database table name.
     */
    public function tableName()
    {
        return 'account_password_history';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('accountId, salt, password', 'required'),
            array('accountId', 'numerical', 'integerOnly' => true),
            array('salt, password', 'length', 'max' => 255),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'account' => array(self::BELONGS_TO, '\nordsoftware\yii_account\models\ar\Account', 'accountId')
        );
    }

    /**
     * Returns the static model of this class.
     * @param string $className active record class name.
     * @return AccountPasswordHistory the static model class.
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
