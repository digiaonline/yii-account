<?php
use WebGuy\UserSteps;

$I = new UserSteps($scenario);
$I->wantTo('Sign up, activate my account and log in.');
$I->register('demo@example.com', 'demo', 'demo1234', 'demo1234');

$I->see('Thank you for registering');
$I->click('a');

$I->seeElement('#loginForm');
$I->login('demo@example.com', 'demo1234');

$I->logout();