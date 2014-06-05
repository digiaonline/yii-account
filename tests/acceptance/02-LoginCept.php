<?php
use WebGuy\UserSteps;

$I = new UserSteps($scenario);
$I->wantTo('Log in and log out.');

// Wait one second before logging in so that our password expires.
sleep(1);

$I->login('demo@example.com', 'demo12');

$I->see('Your password has expired.');
$I->fillField(\ChangePasswordPage::$fieldPassword, 'demo1234');
$I->fillField(\ChangePasswordPage::$fieldVerifyPassword, 'demo1234');
$I->click('#changePasswordForm button[type=submit]');

$I->logout();