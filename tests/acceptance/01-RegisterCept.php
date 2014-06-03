<?php
use WebGuy\UserSteps;

$I = new UserSteps($scenario);
$I->wantTo('Sign up and activate my account.');
$I->register('demo@example.com', 'demo', 'demo1234', 'demo1234');

$I->see('Thank you for registering');
$I->click('a');

$I->seeElement('#loginForm');