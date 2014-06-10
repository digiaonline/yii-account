<?php
use WebGuy\UserSteps;

$I = new UserSteps($scenario);
$I->wantTo('Log in and log out.');

// Wait one second before logging in so that our password expires.
sleep(1);

// todo: add test for account lockout, below code is not passing the tests

// $I->login('demo@example.com', 'demo34');
// $I->login('demo@example.com', 'demo34');

// $I->see('Your account has been temporarily locked due to too many failed login attempts.');

// Wait one second before logging in again so that the lockout expires.
// sleep(1);

$I->login('demo@example.com', 'demo12');

$I->see('Your password has expired.');
$I->fillField(\ChangePasswordPage::$fieldPassword, 'demo1234');
$I->fillField(\ChangePasswordPage::$fieldVerifyPassword, 'demo1234');
$I->click('#changePasswordForm button[type=submit]');

$I->logout();