<?php

namespace nordsoftware\yii_account\controllers;

use nordsoftware\yii_account\exceptions\Exception;
use nordsoftware\yii_account\models\ar\Account;
use nordsoftware\yii_account\Module;
use nordsoftware\yii_account\helpers\Helper;

class RegisterController extends Controller
{
    /**
     * @var string
     */
    public $emailSubject;

    /**
     * @var string
     */
    public $formId = 'registerForm';

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        if ($this->emailSubject === null) {
            $this->emailSubject = Helper::t('email', 'Thank you for registering');
        }
    }

    /**
     * @inheritDoc
     */
    public function filters()
    {
        return array(
            'guestOnly + index',
            'validateToken + activate',
        );
    }

    /**
     * Displays the 'registration' page.
     */
    public function actionIndex()
    {
        $modelClass = $this->module->getClassName(Module::CLASS_REGISTER_FORM);

        /** @var \nordsoftware\yii_account\models\form\RegisterForm $model */
        $model = new $modelClass();

        $request = \Yii::app()->request;

        if ($request->isAjaxRequest && $request->getPost('ajax') === $this->formId) {
            echo \CActiveForm::validate($model);
            \Yii::app()->end();
        }

        if ($request->isPostRequest) {
            $model->attributes = $request->getPost(Helper::classNameToKey($modelClass));

            if ($model->validate()) {
                $accountClass = $this->module->getClassName(Module::CLASS_MODEL);

                /** @var \nordsoftware\yii_account\models\ar\Account $account */
                $account = new $accountClass();
                $account->attributes = $model->attributes;

                if (!$account->save(true, array_keys($model->attributes))) {
                    $this->fatalError();
                }

                if (!$this->module->enableActivation) {
                    $account->markActive();
                    $this->redirect(array('/account/authenticate/login'));
                }

                $this->sendActivationMail($account);
                $this->redirect('done');
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

        $token = $this->generateToken(
            Module::TOKEN_ACTIVATE,
            $account->id,
            Helper::sqlDateTime(time() + $this->module->activateExpireTime)
        );

        $activateUrl = $this->createAbsoluteUrl('/account/register/activate', array('token' => $token));

        $this->module->sendMail(
            $account->email,
            $this->emailSubject,
            $this->renderPartial('/mail/register', array('activateUrl' => $activateUrl))
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

        $modelClass = $this->module->getClassName(Module::CLASS_MODEL);

        /** @var \nordsoftware\yii_account\models\ar\Account $model */
        $model = \CActiveRecord::model($modelClass)->findByPk($tokenModel->accountId);

        if ($model === null) {
            $this->pageNotFound();
        }

        $model->status = Account::STATUS_ACTIVATE;

        if (!$model->save(true, array('status'))) {
            $this->fatalError();
        }

        $tokenModel->markUsed();

        $this->redirect(array('/account/authenticate/login'));
    }
}