<?php
/**
 * WebUser class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.components
 */

namespace nordsoftware\yii_account\components;

use nordsoftware\yii_account\Module;
use nordsoftware\yii_account\exceptions\Exception;
use nordsoftware\yii_account\helpers\Helper;

class WebUser extends \CWebUser
{
    /**
     * @var bool
     */
    public $allowAutoLogin = true;

    /**
     * @var \nordsoftware\yii_account\models\ar\Account
     */
    private $_model;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        if (!$this->isGuest) {
            // Note that saveAttributes can return false if the account is active twice the same second
            // because no attributes are updated, therefore we cannot throw an exception if save fails.
            $this->loadAccount()->saveAttributes(array('lastActiveAt' => Helper::sqlNow()));
        }
    }

    /**
     * Loads the user model for the logged in user.
     *
     * @throws \nordsoftware\yii_account\exceptions\Exception if the user is a guest.
     * @return \nordsoftware\yii_account\models\ar\Account the model.
     */
    public function loadAccount()
    {
        if ($this->isGuest) {
            throw new Exception("Trying to load model for guest user.");
        }

        if (!isset($this->_model)) {
            $modelClass = Helper::getModule()->getClassName(Module::CLASS_ACCOUNT);
            $this->_model = \CActiveRecord::model($modelClass)->findByPk($this->id);
        }

        return $this->_model;
    }
}