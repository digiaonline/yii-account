<?php
/**
 * Account class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.ar.models
 */

namespace nordsoftware\yii_account\models\ar;

use nordsoftware\yii_account\exceptions\Exception;
use nordsoftware\yii_account\helpers\Helper;

/**
 * This is the model class for table "account".
 *
 * The followings are the available columns in table 'account':
 *
 * @property integer $id
 * @property string $salt
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $passwordStrategy
 * @property integer $requireNewPassword
 * @property string $lastLoginAt
 * @property string $lastActiveAt
 * @property integer $status
 *
 * The following are the available methods through \YiiPassword\Behavior:
 *
 * @method bool verifyPassword($password)
 * @method bool changePassword($password, $runValidation)
 */
class Account extends \CActiveRecord
{
    const STATUS_DEFAULT = 0;
    const STATUS_ACTIVATE = 1;

    /**
     * @inheritDoc
     */
    public function tableName()
    {
        return 'account';
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array(
            array('salt, username, password, email, passwordStrategy', 'required'),
            array('requireNewPassword, status', 'numerical', 'integerOnly' => true),
            array('salt, username, password, email, passwordStrategy', 'length', 'max' => 255),
            array('email, username', 'unique'),
            array('lastLoginAt, lastActiveAt', 'safe'),
            array('id, username, email, requireNewPassword, lastLoginAt, lastActiveAt, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array(
            'PasswordBehavior' => array(
                'class' => 'YiiPassword\Behavior',
                'defaultStrategyName' => 'bcrypt',
                'strategies' => array(
                    'bcrypt' => array(
                        'class' => 'YiiPassword\Strategies\Bcrypt',
                        'workFactor' => 12
                    ),
                ),
            ),
        );
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return array(
            'id' => Helper::t('labels', 'ID'),
            'salt' => Helper::t('labels', 'Salt'),
            'username' => Helper::t('labels', 'Username'),
            'password' => Helper::t('labels', 'Password'),
            'email' => Helper::t('labels', 'Email'),
            'passwordStrategy' => Helper::t('labels', 'Password Strategy'),
            'requireNewPassword' => Helper::t('labels', 'Require New Password'),
            'lastLoginAt' => Helper::t('labels', 'Last Login At'),
            'lastActiveAt' => Helper::t('labels', 'Last Active At'),
            'status' => Helper::t('labels', 'Status')
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return \CActiveDataProvider the data provider that can return the models based on the search conditions.
     */
    public function search()
    {
        $criteria = new \CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('requireNewPassword', $this->requireNewPassword);
        $criteria->compare('lastLoginAt', $this->lastLoginAt, true);
        $criteria->compare('lastActiveAt', $this->lastActiveAt, true);
        $criteria->compare('status', $this->status);

        return new \CActiveDataProvider($this, array('criteria' => $criteria));
    }

    /**
     * Returns the static model of this class.
     *
     * @param string $className active record class name.
     * @return \nordsoftware\yii_account\models\ar\Account the static model class.
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
