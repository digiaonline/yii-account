<?php
namespace WebGuy;

class UserSteps extends \WebGuy
{
    function signup($email, $username, $password, $verifyPassword)
    {
        $I = $this;

        $I->amOnPage(\SignupPage::$URL);
        $I->fillField(\SignupPage::$fieldEmail, $email);
        $I->fillField(\SignupPage::$fieldUsername, $username);
        $I->fillField(\SignupPage::$fieldPassword, $password);
        $I->fillField(\SignupPage::$fieldVerifyPassword, $verifyPassword);
        $I->click(\SignupPage::$buttonSubmit);
    }

    function login($username, $password)
    {
        $I = $this;

        $I->amOnPage(\LoginPage::$URL);
        $I->fillField(\LoginPage::$fieldUsername, $username);
        $I->fillField(\LoginPage::$fieldPassword, $password);
        $I->click(\LoginPage::$buttonSubmit);
    }

    function logout()
    {
        $I = $this;

        $I->amOnPage(\LogoutPage::$URL);
    }
}