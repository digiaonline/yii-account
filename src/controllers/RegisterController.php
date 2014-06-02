<?php

namespace nordsoftware\yii_account\controllers;

use nordsoftware\yii_account\exceptions\Exception;
use nordsoftware\yii_account\models\ar\Account;
use nordsoftware\yii_account\Module;
use nordsoftware\yii_account\helpers\Helper;

class RegisterController extends Controller
{
    public $emailSubject;

    /**
     * @var string
     */
    public $formId = 'registerForm';

    /**
     * @var string
     */
    public $layout = 'narrow';

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

            $accountModelClass = $this->module->getClassName(Module::CLASS_MODEL);

            /** @var \nordsoftware\yii_account\models\ar\Account $account */
            $account = new $accountModelClass();
            $account->attributes = $model->attributes;

            if (!$account->save()) {
                throw new Exception("Failed to create account.");
            }

            $token = $this->generateToken(
                Module::TOKEN_ACTIVATE,
                $account->id,
                Helper::sqlDateTime(time() + $this->module->activateExpireTime)
            );

            $activateUrl = $this->createUrl('/account/activate', array('token' => $token));

            $this->module->sendMail(
                $account->email,
                $this->emailSubject,
                $this->renderPartial('/mail/register', array('activateUrl' => $activateUrl))
            );

            $this->redirect('done');
        }

        $this->render('index', array('model' => $model));
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
     */
    public function actionActivate()
    {
        $tokenModel = $this->loadToken(Module::TOKEN_ACTIVATE, \Yii::app()->request->getQuery('token'));

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

        $this->redirect(array('/account/authenticate'));
    }
}