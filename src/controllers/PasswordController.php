<?php

namespace nordsoftware\yii_account\controllers;

use nordsoftware\yii_account\helpers\Helper;
use nordsoftware\yii_account\Module;

class PasswordController extends Controller
{
    /**
     * @var string
     */
    public $forgotFormId = 'forgotPasswordForm';

    /**
     * @var string
     */
    public $changeFormId = 'changePasswordForm';

    /**
     * @inheritDoc
     */
    public function filters()
    {
        return array(
            'guestOnly + index',
            'validateToken + change',
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

            // todo: send email with instructions to change the password
        }

        $this->render('forgot', array('model' => $model));
    }

    /**
     * Displays the 'change password' page.
     */
    public function actionChange()
    {
        $modelClass = $this->module->getClassName(Module::CLASS_CHANGE_PASSWORD_FORM);

        /** @var \nordsoftware\yii_account\models\form\ChangePasswordForm $model */
        $model = new $modelClass();

        $request = \Yii::app()->request;

        if ($request->isAjaxRequest && $request->getPost('ajax') === $this->changeFormId) {
            echo \CActiveForm::validate($model);
            \Yii::app()->end();
        }

        if ($request->isPostRequest) {
            $model->attributes = $request->getPost(Helper::classNameToKey($modelClass));

            // todo: change the password and forward the user to login.
        }

        $this->render('change', array('model' => $model));
    }
} 