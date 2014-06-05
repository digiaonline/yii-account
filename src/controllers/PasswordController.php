<?php
/**
 * PasswordController class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.controllers
 */

namespace nordsoftware\yii_account\controllers;

use nordsoftware\yii_account\helpers\Helper;
use nordsoftware\yii_account\models\ar\AccountToken;
use nordsoftware\yii_account\models\form\ChangePasswordForm;
use nordsoftware\yii_account\Module;

class PasswordController extends Controller
{
    /**
     * @var string
     */
    public $emailSubject;

    /**
     * @var string
     */
    public $forgotFormId = 'forgotPasswordForm';

    /**
     * @var string
     */
    public $changeFormId = 'changePasswordForm';

    /**
     * @var string
     */
    public $defaultAction = 'forgot';

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        if ($this->emailSubject === null) {
            $this->emailSubject = Helper::t('email', 'Reset password');
        }
    }

    /**
     * @inheritDoc
     */
    public function filters()
    {
        return array(
            'guestOnly + index',
            'ensureToken + reset, change',
        );
    }

    /**
     * Displays the 'forgot password' page.
     */
    public function actionForgot()
    {
        $modelClass = $this->module->getClassName(Module::CLASS_FORGOT_PASSWORD_FORM);

        /** @var \nordsoftware\yii_account\models\form\ForgotPasswordForm $model */
        $model = new $modelClass();

        $request = \Yii::app()->request;

        if ($request->isAjaxRequest && $request->getPost('ajax') === $this->forgotFormId) {
            echo \CActiveForm::validate($model);
            \Yii::app()->end();
        }

        if ($request->isPostRequest) {
            $model->attributes = $request->getPost(Helper::classNameToKey($modelClass));

            if ($model->validate()) {
                $accountClass = $this->module->getClassName(Module::CLASS_MODEL);

                /** @var \nordsoftware\yii_account\models\ar\Account $account */
                $account = \CActiveRecord::model($accountClass)->findByAttributes(array('email' => $model->email));

                $token = $this->module->generateToken(Module::TOKEN_RESET_PASSWORD, $account->id);

                $resetUrl = $this->createAbsoluteUrl('/account/password/reset', array('token' => $token));

                $this->module->sendMail(
                    $account->email,
                    $this->emailSubject,
                    $this->renderPartial('/email/resetPassword', array('resetUrl' => $resetUrl), true)
                );

                $this->redirect('sent');
            }
        }

        $this->render('forgot', array('model' => $model));
    }

    /**
     * Displays the 'reset password' page.
     *
     * @param string $token authentication token.
     */
    public function actionReset($token)
    {
        $tokenModel = $this->loadToken(Module::TOKEN_RESET_PASSWORD, $token);

        if ($this->module->hasTokenExpired($tokenModel, $this->module->resetPasswordExpireTime)) {
            $this->accessDenied();
        }

        $model = $this->changePasswordInternal($tokenModel);

        $this->render('reset', array('model' => $model));
    }

    /**
     * Displays the 'change password' page.
     *
     * @param string $token authentication token.
     */
    public function actionChange($token)
    {
        $tokenModel = $this->loadToken(Module::TOKEN_CHANGE_PASSWORD, $token);

        if ($this->module->hasTokenExpired($tokenModel, 3600/* 1h */)) {
            $this->accessDenied();
        }

        $model = $this->changePasswordInternal($tokenModel);

        $this->render('change', array('model' => $model));
    }

    /**
     * Performs logic to change the password for an account.
     *
     * @param \nordsoftware\yii_account\models\ar\AccountToken $tokenModel authentication token model.
     * @return \nordsoftware\yii_account\models\form\PasswordForm the form model.
     */
    protected function changePasswordInternal(AccountToken $tokenModel)
    {
        $modelClass = $this->module->getClassName(Module::CLASS_PASSWORD_FORM);

        /** @var \nordsoftware\yii_account\models\form\PasswordForm $model */
        $model = new $modelClass();

        $request = \Yii::app()->request;

        if ($request->isAjaxRequest && $request->getPost('ajax') === $this->changeFormId) {
            echo \CActiveForm::validate($model);
            \Yii::app()->end();
        }

        if ($request->isPostRequest) {
            $model->attributes = $request->getPost(Helper::classNameToKey($modelClass));

            if ($model->validate()) {
                $accountClass = $this->module->getClassName(Module::CLASS_MODEL);

                /** @var \nordsoftware\yii_account\models\ar\Account $account */
                $account = \CActiveRecord::model($accountClass)->findByPk($tokenModel->accountId);

                // Check that the password has not been used in the past.
                if ($model->checkPasswordHistory($account, $model->password)) {
                    $model->addError('password', Helper::t('errors', 'You have already used this password.'));
                }

                if (!$model->hasErrors() && $account->changePassword($model->password, true)) {
                    $model->createHistoryEntry($account->id, $account->salt, $account->password);

                    // We need to reset the requireNewPassword flag if applicable when the password has been changed.
                    if ($account->requireNewPassword && !$account->saveAttributes(array('requireNewPassword' => false))) {
                        $this->fatalError();
                    }

                    if (!$tokenModel->saveAttributes(array('status' => AccountToken::STATUS_USED))) {
                        $this->fatalError();
                    }

                    $this->redirect(array('/account/authenticate/login'));
                }

                // todo: figure out how to avoid this, the problem is that password validation is done on the account

                $model->addError('password', $account->getError('password'));
            }
        }

        return $model;
    }
}