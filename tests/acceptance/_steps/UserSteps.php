<?php
namespace WebGuy;

class UserSteps extends \WebGuy
{
    function register($email, $username, $password, $verifyPassword)
    {
        $I = $this;

        $I->amOnPage(\RegisterPage::$URL);
        $I->fillField(\RegisterPage::$fieldEmail, $email);
        $I->fillField(\RegisterPage::$fieldUsername, $username);
        $I->fillField(\RegisterPage::$fieldPassword, $password);
        $I->fillField(\RegisterPage::$fieldVerifyPassword, $verifyPassword);
        $I->click(\RegisterPage::$buttonSubmit);
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