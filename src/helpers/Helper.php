<?php
/**
 * Helper class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.helpers
 */

namespace nordsoftware\yii_account\helpers;

use nordsoftware\yii_account\Module;

class Helper
{
    /**
     * @var \CWebModule parent module for the account module.
     */
    public static $module;

    /**
     * Returns the current time by querying it from the database.
     *
     * @return string current time.
     */
    public static function sqlNow()
    {
        return \Yii::app()->db->createCommand('SELECT NOW()')->queryScalar();
    }

    /**
     * Converts a class name to the key used when POSTING data for the class.
     *
     * @param string $className class name.
     * @return string post key.
     */
    public static function classNameToPostKey($className)
    {
        return str_replace('\\', '_', ltrim($className, '\\'));
    }

    /**
     * Translates the the given text.
     *
     * @param string $category message category.
     * @param string $message text to translate.
     * @param array $params additional parameters.
     * @return string translated text.
     */
    public static function t($category, $message, $params = array())
    {
        return \Yii::t(
            '\nordsoftware\yii_account\Module.' . $category,
            $message,
            $params,
            self::getModule()->messageSource
        );
    }

    /**
     * Returns the account module.
     *
     * @return \nordsoftware\yii_account\Module module instance.
     */
    public static function getModule()
    {
        if (!isset(self::$module)) {
            self::$module = \Yii::app();
        }

        return self::$module->getModule(Module::MODULE_ID);
    }
}