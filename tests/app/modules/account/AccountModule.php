<?php

use nordsoftware\yii_account\Module;

class AccountModule extends Module
{
    /**
     * @var string
     */
    public $fromEmailAddress = 'noreply@example.com';

    /**
     * @inheritDoc
     */
    public function sendMail($to, $subject, $body, array $config = array())
    {
        echo $body;
        die;
    }
}