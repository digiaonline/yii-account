<?php
use WebGuy\UserSteps;

$I = new UserSteps($scenario);
$I->wantTo('Sign up and activate my account.');
$I->signup('demo@example.com', 'demo', 'demo12', 'demo12');

$I->see('Thank you for signing up');
$I->click('a');

$I->seeElement('#loginForm');