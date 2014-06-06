<?php
use WebGuy\UserSteps;

$I = new UserSteps($scenario);
$I->wantTo('Reset my password and log in.');

$I->amOnPage(\ForgotPasswordPage::$URL);
$I->fillField(\ForgotPasswordPage::$fieldEmail, 'demo@example.com');
$I->click('#forgotPasswordForm button[type=submit]');

$I->see('Reset password');
$I->click('a');

$I->seeElement('#changePasswordForm');
$I->fillField(\ResetPasswordPage::$fieldPassword, 'demo1234');
$I->fillField(\ResetPasswordPage::$fieldVerifyPassword, 'demo1234');
$I->click('#changePasswordForm button[type=submit]');

$I->see('You have already used this password.');

$I->fillField(\ResetPasswordPage::$fieldPassword, 'demo4321');
$I->fillField(\ResetPasswordPage::$fieldVerifyPassword, 'demo4321');
$I->click('#changePasswordForm button[type=submit]');

$I->login('demo@example.com', 'demo4321');