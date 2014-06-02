<?php

namespace nordsoftware\yii_account\helpers;

use nordsoftware\yii_account\Module;

class Helper
{
    /**
     * @param integer $timestamp
     * @return string
     */
    public static function sqlDateTime($timestamp = null)
    {
        return date('Y-m-d H:i:s', $timestamp !== null ? $timestamp : time());
    }

    /**
     * @param string $className
     * @return string
     */
    public static function classNameToKey($className)
    {
        return str_replace('\\', '_', ltrim($className, '\\'));
    }

    /**
     * @param string $category
     * @param string $message
     * @param array $params
     * @return string
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
     * @return \nordsoftware\yii_account\Module
     */
    public static function getModule()
    {
        return \Yii::app()->getModule(Module::MODULE_ID);
    }
}