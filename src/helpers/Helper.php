<?php

namespace nordsoftware\yii_account\helpers;

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
     * @param string $category
     * @param string $message
     * @param array $params
     * @param string $source
     * @param string $language
     * @return string
     */
    public static function t($category, $message, $params = array(), $source = null, $language = null)
    {
        return \Yii::t('\nordsoftware\yii_account\AccountModule' . $category, $message, $params, $source, $language);
    }
} 