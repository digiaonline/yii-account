<?php

namespace nordsoftware\yii_account\helpers;

class Helper
{
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