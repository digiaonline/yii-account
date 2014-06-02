<?php

use nordsoftware\yii_account\Module;

class AccountModule extends Module
{
    /**
     * @var string
     */
    public $fromEmailAddress = 'noreply@example.com';

    /**
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param array $config
     * @return bool
     * @throws nordsoftware\yii_account\exceptions\Exception
     */
    public function sendMail($to, $subject, $body, array $config = array())
    {
        echo $subject . '<br><br>' . $body;
    }
}