<?php
/**
 * AccountCommand class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.commands
 */

namespace nordsoftware\yii_account\commands;

use nordsoftware\yii_account\Module;
use nordsoftware\yii_account\exceptions\Exception;
use nordsoftware\yii_account\helpers\Helper;

class AccountCommand extends \CConsoleCommand
{
    /**
     * @var string
     */
    public $defaultAction = 'create';

    /**
     * Creates a new account with the given username and password.
     *
     * @param string $username
     * @param string $password
     * @throws \nordsoftware\yii_account\exceptions\Exception
     */
    public function actionCreate($username, $password)
    {
        $modelClass = Helper::getModule()->getClassName(Module::CLASS_ACCOUNT);

        /** @var \nordsoftware\yii_account\models\ar\Account $account */
        $account = new $modelClass();
        $account->username = $username;
        $account->password = $password;

        if (!$account->save(false)) {
            throw new Exception("Failed to create account.");
        }

        echo "Account $username:$password created.\n";
    }
}
