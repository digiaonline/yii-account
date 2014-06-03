<?php
use WebGuy\UserSteps;

$I = new UserSteps($scenario);
$I->wantTo('Log in and log out.');

$I->login('demo@example.com', 'demo1234');

$I->logout();