<?php
/**
 * SignupController class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.controllers
 */

namespace nordsoftware\yii_account\controllers;

use nordsoftware\yii_account\models\ar\Account;
use nordsoftware\yii_account\models\ar\AccountToken;
use nordsoftware\yii_account\Module;
use nordsoftware\yii_account\helpers\Helper;

class SignupController extends Controller
{
    /**
     * @var string
     */
    public $emailSubject;

    /**
     * @var string
     */
    public $formId = 'signupForm';

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        if ($this->emailSubject === null) {
            $this->emailSubject = Helper::t('email', 'Thank you for signing up');
        }
    }

    /**
     * @inheritDoc
     */
    public function filters()
    {
        return array(
            'guestOnly + index',
            'ensureToken + activate',
        );
    }

    /**
     * Displays the 'sign up' page.
     */
    public function actionIndex()
    {
        $modelClass = $this->module->getClassName(Module::CLASS_SIGNUP_FORM);

        /** @var \nordsoftware\yii_account\models\form\SignupForm $model */
        $model = new $modelClass();

        $request = \Yii::app()->request;

        $this->runAjaxValidation($model, $this->formId);

        if ($request->isPostRequest) {
            $model->attributes = $request->getPost(Helper::classNameToPostKey($modelClass));

            if ($model->validate()) {
                $accountClass = $this->module->getClassName(Module::CLASS_ACCOUNT);

                /** @var \nordsoftware\yii_account\models\ar\Account $account */
                $account = new $accountClass();
                $account->attributes = $model->attributes;

                if ($account->validate()) {
                    if (!$account->save(false/* already validated */)) {
                        $this->fatalError();
                    }

                    $model->createHistoryEntry($account->id, $account->salt, $account->password);

                    if (!$this->module->enableActivation) {
                        $account->saveAttributes(array('status' => Account::STATUS_ACTIVATED));
                        $this->redirect(array('/account/authenticate/login'));
                    }

                    $this->sendActivationMail($account);
                    $this->redirect(array('done'));
                }

                // todo: figure out how to avoid this, the problem is that password validation is done on the account

                foreach ($account->getErrors() as $attribute => $errors) {
                    foreach ($errors as $error) {
                        $model->addError($attribute, $error);
                    }
                }
            }
        }

        $this->render('index', array('model' => $model));
    }

    /**
     * Sends the activation email to the given account.
     *
     * @param Account $account account model.
     * @throws \nordsoftware\yii_account\exceptions\Exception
     */
    protected function sendActivationMail(Account $account)
    {
        if (!$account->save(false)) {
            $this->fatalError();
        }

        $token = $this->module->generateToken(Module::TOKEN_ACTIVATE, $account->id);

        $activateUrl = $this->createAbsoluteUrl('/account/signup/activate', array('token' => $token));

        $this->module->sendMail(
            $account->email,
            $this->emailSubject,
            $this->renderPartial('/email/activate', array('activateUrl' => $activateUrl), true)
        );
    }

    /**
     * Displays the 'done' page.
     */
    public function actionDone()
    {
        $this->render('done');
    }

    /**
     * Actions to take when activating an account.
     *
     * @param string $token authentication token.
     */
    public function actionActivate($token)
    {
        $tokenModel = $this->loadToken(Module::TOKEN_ACTIVATE, $token);

        if ($this->module->hasTokenExpired($tokenModel, $this->module->activateExpireTime)) {
            $this->accessDenied();
        }

        $modelClass = $this->module->getClassName(Module::CLASS_ACCOUNT);

        /** @var \nordsoftware\yii_account\models\ar\Account $model */
        $model = \CActiveRecord::model($modelClass)->findByPk($tokenModel->accountId);

        if ($model === null) {
            $this->pageNotFound();
        }

        $model->status = Account::STATUS_ACTIVATED;

        if (!$model->save(true, array('status'))) {
            $this->fatalError();
        }

        if (!$tokenModel->saveAttributes(array('status' => AccountToken::STATUS_USED))) {
            $this->fatalError();
        }

        $this->redirect(array('/account/authenticate/login'));
    }
}