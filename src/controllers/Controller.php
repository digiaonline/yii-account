<?php
/**
 * Controller class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.controllers
 */

namespace nordsoftware\yii_account\controllers;

use nordsoftware\yii_account\helpers\Helper;
use nordsoftware\yii_account\Module;

/**
 * @property \nordsoftware\yii_account\Module $module
 */
class Controller extends \CController
{
    // todo: consider rising events e.g. after register, etc.

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        if ($this->layout === null) {
            $this->layout = $this->module->defaultLayout;
        }
    }

    /**
     * @param \CFilterChain $filterChain
     */
    public function filterGuestOnly(\CFilterChain $filterChain)
    {
        if (!\Yii::app()->user->isGuest) {
            $this->redirect(\Yii::app()->homeUrl);
        }

        $filterChain->run();
    }

    /**
     * @param \CFilterChain $filterChain
     */
    public function filterAuthenticatedOnly(\CFilterChain $filterChain)
    {
        if (\Yii::app()->user->isGuest) {
            $this->redirect(\Yii::app()->homeUrl);
        }

        $filterChain->run();
    }

    /**
     * @param \CFilterChain $filterChain
     */
    public function filterEnsureToken(\CFilterChain $filterChain)
    {
        if (($token = \Yii::app()->request->getQuery('token')) === null) {
            $this->accessDenied(Helper::t('errors', 'Invalid authentication token.'));
        }

        $filterChain->run();
    }

    /**
     * Loads a token of a specific type.
     *
     * @param string $type token type.
     * @param string $token token string.
     * @return \nordsoftware\yii_account\models\ar\AccountToken
     */
    public function loadToken($type, $token)
    {
        $model = $this->module->loadToken($type, $token);

        if ($model === null) {
            $this->accessDenied(Helper::t('errors', 'Invalid authentication token.'));
        }

        return $model;
    }

    /**
     * @param string $message error message.
     * @throws \CHttpException when called.
     */
    public function accessDenied($message = null)
    {
        throw new \CHttpException(401, $message === null ? Helper::t('errors', 'Access denied.') : $message);
    }

    /**
     * @param string $message error message.
     * @throws \CHttpException when called.
     */
    public function pageNotFound($message = null)
    {
        throw new \CHttpException(404, $message === null ? Helper::t('errors', 'Page not found.') : $message);
    }

    /**
     * @param string $message error message.
     * @throws \CHttpException when called.
     */
    public function fatalError($message = null)
    {
        throw new \CHttpException(500, $message === null ? Helper::t('errors', 'Something went wrong.') : $message);
    }

    /**
     * Runs validation on the given model if the request is an AJAX request.
     *
     * @param \CModel $model model instance.
     * @param string $formId form id.
     */
    public function runAjaxValidation(\CModel $model, $formId)
    {
        if (\Yii::app()->request->isAjaxRequest && \Yii::app()->request->getPost('ajax') === $formId) {
            echo \CActiveForm::validate($model);
            \Yii::app()->end();
        }
    }

    /**
     * Loads a specific account model.
     *
     * @param int $id account identifier.
     * @throws \CHttpException if the account model cannot be found.
     * @return \nordsoftware\yii_account\models\ar\Account
     */
    public function loadModel($id)
    {
        $modelClass = $this->module->getClassName(Module::CLASS_MODEL);
        $model = \CActiveRecord::model($modelClass)->findByPk($id);

        if ($model === null) {
            $this->pageNotFound();
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function getLayoutFile($layoutName)
    {
        if (($layoutFile = parent::getLayoutFile($layoutName)) === false) {
            $layoutFile = $this->getDefaultViewFile($layoutName, 'layouts');
        }

        return $layoutFile;
    }

    /**
     * @inheritDoc
     */
    public function getViewFile($viewName)
    {
        if (($viewFile = parent::getViewFile($viewName)) === false) {
            $viewFile = $this->getDefaultViewFile($viewName, $this->getId());
        }

        return $viewFile;
    }

    /**
     * Returns the path to the default view file.
     *
     * @param string $viewName view name.
     * @param string $viewPath view path.
     * @return string path to the view.
     */
    protected function getDefaultViewFile($viewName, $viewPath)
    {
        $moduleViewPath = dirname(__DIR__) . '/views';
        return $this->resolveViewFile($viewName, "{$moduleViewPath}/{$viewPath}", $moduleViewPath);
    }
}