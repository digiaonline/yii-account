<?php

namespace nordsoftware\yii_account\controllers;

use nordsoftware\yii_account\Module;
use nordsoftware\yii_account\helpers\Helper;

class AuthenticateController extends Controller
{
    /**
     * @var string
     */
    public $loginFormId = 'loginForm';

    /**
     * @var string
     */
    public $layout = 'narrow';

    /**
     * @var string
     */
    public $defaultAction = 'login';

    /**
     * @inheritDoc
     */
    public function filters()
    {
        return array(
            'guestOnly + login',
            'authenticatedOnly + logout',
        );
    }

    /**
     * Displays the 'login' page.
     */
    public function actionLogin()
    {
        $modelClass = $this->module->getClassName(Module::CLASS_LOGIN_FORM);

        /** @var \nordsoftware\yii_account\models\form\LoginForm $model */
        $model = new $modelClass();

        $request = \Yii::app()->request;

        if ($request->isAjaxRequest && $request->getPost('ajax') === $this->loginFormId) {
            echo \CActiveForm::validate($model);
            \Yii::app()->end();
        }

        if ($request->isPostRequest) {
            $model->attributes = $request->getPost(Helper::classNameToKey($modelClass));

            if ($model->validate() && $model->login()) {
                \Yii::app()->user->updateLastLoginAt();
                $this->redirect(\Yii::app()->user->returnUrl);
            }
        }

        $this->render('login', array('model' => $model));
    }

    /**
     * Action that logs the user out.
     */
    public function actionLogout()
    {
        \Yii::app()->user->logout();
        $this->redirect(\Yii::app()->homeUrl);
    }
}
