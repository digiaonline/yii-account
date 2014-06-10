<?php
/**
 * LoginForm class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.form.models
 */

namespace nordsoftware\yii_account\models\form;

use nordsoftware\yii_account\exceptions\Exception;
use nordsoftware\yii_account\Module;
use nordsoftware\yii_account\helpers\Helper;

class LoginForm extends \CFormModel
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var boolean
     */
    public $stayLoggedIn;

    /**
     * @var \nordsoftware\yii_account\components\UserIdentity
     */
    private $_identity;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array(
            array('username, password', 'required'),
            array('stayLoggedIn', 'boolean'),
            array('password', 'authenticate'),
        );
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return array(
            'username' => Helper::t('labels', 'Username'),
            'password' => Helper::t('labels', 'Password'),
            'stayLoggedIn' => Helper::t('labels', 'Stay logged in'),
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $identityClass = Helper::getModule()->getClassName(Module::CLASS_USER_IDENTITY);

            $this->_identity = new $identityClass($this->username, $this->password);

            $success = $this->_identity->authenticate();
            $account = $this->_identity->getAccount();

            if ($account !== null && $this->isAccountLocked($account->id)) {
                $this->addError('password', Helper::t('errors', 'Your account has been temporarily locked due to too many failed login attempts.'));
            }

            if (!$success) {
                $this->createHistoryEntry($account !== null ? $account->id : 0, false);
                $this->addError('password', Helper::t('errors', 'Your username or password is invalid.'));
            }
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     *
     * @throws \nordsoftware\yii_account\exceptions\Exception if identity is not set.
     * @return boolean whether login is successful
     */
    public function login()
    {
        $module = Helper::getModule();

        if ($this->_identity === null) {
            throw new Exception('Failed to login account.');
        }

        if ($this->_identity->errorCode !== \CUserIdentity::ERROR_NONE) {
            return false;
        }

        $this->createHistoryEntry($this->_identity->getId(), true);

        $duration = $this->stayLoggedIn ? $module->loginExpireTime : 0; // 30 days
        \Yii::app()->user->login($this->_identity, $duration);

        return true;
    }

    /**
     * Returns whether a given account has been locked out.
     *
     * @param int $accountId account id.
     * @return bool whether the account is locked.
     */
    public function isAccountLocked($accountId)
    {
        $module = Helper::getModule();

        if ($module->numAllowedFailedLogins === 0) {
            return false;
        }

        $criteria = new \CDbCriteria();
        $criteria->addCondition('accountId=:accountId');
        $criteria->params[':accountId'] = $accountId;
        $criteria->order = 'createdAt DESC';

        $modelClass = Helper::getModule()->getClassName(Module::CLASS_LOGIN_HISTORY);

        /** @var \nordsoftware\yii_account\models\ar\AccountLoginHistory $model */
        $model = \CActiveRecord::model($modelClass)->find($criteria);

        if ($model === null) {
            return false;
        }

        if ($model->numFailedAttempts <= $module->numAllowedFailedLogins) {
            return false;
        }

        return strtotime(Helper::sqlNow()) - strtotime($model->createdAt) < $module->lockoutExpireTime;
    }

    /**
     * Returns whether the password for a specific account has expired.
     *
     * @param int $accountId account id.
     * @throws \nordsoftware\yii_account\exceptions\Exception if no password history model can be found.
     * @return bool whether the password has expired.
     */
    public function hasPasswordExpired($accountId)
    {
        $module = Helper::getModule();

        if ($module->passwordExpireTime === 0) {
            return false;
        }

        $criteria = new \CDbCriteria();
        $criteria->addCondition('accountId=:accountId');
        $criteria->params[':accountId'] = $accountId;
        $criteria->order = 'createdAt DESC';

        $modelClass = Helper::getModule()->getClassName(Module::CLASS_PASSWORD_HISTORY);

        /** @var \nordsoftware\yii_account\models\ar\AccountPasswordHistory $model */
        $model = \CActiveRecord::model($modelClass)->find($criteria);

        if ($model === null) {
            throw new Exception('Failed to check if password has expired.');
        }

        return strtotime(Helper::sqlNow()) - strtotime($model->createdAt) > $module->passwordExpireTime;
    }

    /**
     * Creates a login history entry.
     *
     * @param int $accountId account id.
     * @param bool $success whether login was successful.
     * @throws \nordsoftware\yii_account\exceptions\Exception if the history entry cannot be saved.
     */
    protected function createHistoryEntry($accountId, $success)
    {
        $modelClass = Helper::getModule()->getClassName(Module::CLASS_LOGIN_HISTORY);

        /** @var \nordsoftware\yii_account\models\ar\AccountLoginHistory $model */
        $model = new $modelClass();
        $model->accountId = $accountId;
        $model->success = (int) $success;
        $model->numFailedAttempts = $success || $accountId === 0 ? 0 : $model->resolveNumFailedAttempts();

        if (!$model->save()) {
            throw new Exception('Failed to save login history entry.');
        }
    }
}