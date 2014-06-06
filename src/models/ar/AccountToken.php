<?php
/**
 * AccountToken class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.ar.models
 */

namespace nordsoftware\yii_account\models\ar;

use nordsoftware\yii_account\helpers\Helper;

/**
 * This is the model class for table AccountToken".
 *
 * The followings are the available columns in table 'account_token':
 *
 * @property integer $id
 * @property integer $accountId
 * @property string $type
 * @property string $token
 * @property string $createdAt
 * @property integer $status
 *
 * The followings are the available model relations:
 *
 * @property \nordsoftware\yii_account\models\ar\Account $account
 */
class AccountToken extends \CActiveRecord
{
    const STATUS_UNUSED = 0;
    const STATUS_USED = 1;

    /**
     * @inheritDoc
     */
    public function tableName()
    {
        return 'account_token';
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array(
            array('accountId, type, token', 'required'),
            array('accountId, status', 'numerical', 'integerOnly' => true),
            array('type, token', 'length', 'max' => 255),
        );
    }

    /**
     * @inheritDoc
     */
    public function relations()
    {
        return array(
            'account' => array(self::BELONGS_TO, '\nordsoftware\yii_account\models\Account', 'accountId')
        );
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return array(
            'id' => Helper::t('labels', 'ID'),
            'accountId' => Helper::t('labels', 'Account'),
            'type' => Helper::t('labels', 'Type'),
            'token' => Helper::t('labels', 'Token'),
            'createdAt' => Helper::t('labels', 'Created At'),
            'status' => Helper::t('labels', 'Status')
        );
    }

    /**
     * Returns the static model of this class.
     *
     * @param string $className active record class name.
     * @return AccountToken the static model class.
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
