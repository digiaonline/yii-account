<?php
/**
 * AuthenticateController class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.controllers
 */

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

        $this->runAjaxValidation($model, $this->loginFormId);

        if ($request->isPostRequest) {
            $model->attributes = $request->getPost(Helper::classNameToPostKey($modelClass));

            if ($model->validate() && $model->login()) {
                /** @var \nordsoftware\yii_account\models\ar\Account $account */
                $account = \Yii::app()->user->loadAccount();

                // Check if the password has expired and require a password change if necessary.
                if ($model->hasPasswordExpired($account->id)) {
                    $account->saveAttributes(array('requireNewPassword' => true));
                }

                // Redirect the logged in user to change the password if it needs to be changed.
                if ($account->requireNewPassword) {
                    $token = $this->module->generateToken(Module::TOKEN_CHANGE_PASSWORD, $account->id);

                    // Logout the user to deny access to restricted actions until the password has been changed.
                    \Yii::app()->user->logout();

                    $this->redirect(array('/account/password/change', 'token' => $token));
                }

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
