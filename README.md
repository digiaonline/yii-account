yii-account
===========

[![Latest Stable Version](https://poser.pugx.org/nordsoftware/yii-account/version.svg)](https://packagist.org/packages/nordsoftware/yii-account)
[![Build Status](https://travis-ci.org/nordsoftware/yii-account.svg?branch=master)](https://travis-ci.org/nordsoftware/yii-account)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nordsoftware/yii-account/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nordsoftware/yii-account/?branch=master)

Extension that provides basic account functionality for the Yii PHP framework.

Why do I want this
------------------

This project was inspired by the [http://github.com/mishamx/yii-user](yii-user module) and was carefully developed 
with our expertise in Yii following the best practices of the framework. It is more secure because it uses passwords
with salt that are encrypted using bcrypt instead of password hashes. It also comes with support for sending mail with 
truly random authentication tokens that expire.

We are also currently working on additional security features (listed in the requirements below).

User interface
--------------

![Login](https://raw.githubusercontent.com/nordsoftware/yii-account/develop/screenshots/login.png)<br />
![Signup](https://raw.githubusercontent.com/nordsoftware/yii-account/develop/screenshots/signup.png)<br />
![Forgot password](https://raw.githubusercontent.com/nordsoftware/yii-account/develop/screenshots/forgot-password.png)<br />
![Change password](https://raw.githubusercontent.com/nordsoftware/yii-account/develop/screenshots/change-password.png)

Requirements
------------

- Secure accounts (password + salt) __DONE__
- Sign up __DONE__
- Account activation (enabled by default) __DONE__
- Log in / Log out __DONE__
- Reset password __DONE__
- Email sending (with token validation) __DONE__
- Require new password every x days (disabled by default) __DONE__
- Password history (encrypted) to prevent from using same password twice __DONE__
- Lock accounts after x failed login attempts (disabled by default) __DONE___
- Console command for creating accounts __DONE__
- Proper README __WIP__

Installation
------------

The easiest way to install this extension is to use Composer.

Run the following command to download the extension:

```bash
php composer.phar require nordsoftware/yii-account:*
```

Add the following to your application configuration:

```php
'modules' => array(
    'account' => array(
        'class' => '\nordsoftware\yii_account\Module',
    ),
),
'components' => array(
    'user' => array(
        'class' => '\nordsoftware\yii_account\components\WebUser',
    ),
),
```

To use the console command you need to add the following to your console application configuration:

```php
'commandMap' => array(
    'account' => array(
        'class' => '\nordsoftware\yii_account\commands\AccountCommand',
    ),
),
```

If you are not using Composer, then you need to download the dependencies manually 
and add the following to your application configuration:

```php
'aliases' => array(
    '\nordsoftware\yii_account' => __DIR__ . '/relative/path/to/yii-account/src',
    '\YiiPassword' => __DIR__ . '/relative/path/to/yiipassword/src',
    '\RandomLib' => __DIR__ . '/relative/path/to/randomlib/lib',
    '\SecureLib' => __DIR__ . '/relative/path/to/securelib/lib',
),
```

### Dependencies

- Yiistrap http://github.com/crisu83/yiistrap
- YiiPassword https://github.com/phpnode/yiipassword
- RandomLib https://github.com/ircmaxell/RandomLib
- SecurityLib https://github.com/ircmaxell/SecurityLib

Run the following command to apply database migrations:

```bash
php yiic.php migrate --migrationPath=account.migrations
```

### Configuration

The following configurations are available for the ```\nordsoftware\yii_account\Module``` class:

 * __classMap__ _array_ map over classes to use within the module.
 * __enableActivation__ _bool_ whether to enable account activation (defaults to true).
 * __numAllowedFailedLogins__ _int_ number of a user can fail to login before the account is locked (defaults to 10)
 * __loginExpireTime__ _int_ number of seconds for login cookie to expire (defaults to 30 days).
 * __activateExpireTime__ _int_ number of seconds for account activation to expire (defaults to 30 days).
 * __resetPasswordExpireTime__ _int_ number of seconds for password reset to expire (defaults to 1 day).
 * __passwordExpireTime__ _int_ number of seconds for passwords to expire (defaults to disabled).
 * __lockoutExpireTime__ _int_ number of seconds for account lockout to expire (defaults to 10 minutes).
 * __fromEmailAddress__ _string_ from e-mail address used when sending mail.
 * __messageSource__ _string_ message source component to use for the module.
 * __registerStyles__ _bool_ whether to register the default styles.
 * __defaultLayout__ _string_ path alias for the layout to use within the module.

Usage
-----

Now you should be able to see the login page when you go to the following url:

```bash
index.php?r=account
```

You can run the following command to generate an account from the command line:

```bash
php yiic.php account create --username=demo --password=demo
```

Extending
---------

This project was developed with a focus on re-usability, so while we are working on the documentation feel free to
dive into the code to find out how to extend this module properly. If you find yourself replacing numerous classes 
with your own you are probably doing something wrong, almost everything can be done through simple configuration.

Contribute
----------

If you wish to contribute to this project feel free to create a pull-request to the ```develop``` branch.

### Run test suite

To run the test suite you need to run the following commands:

```bash
export DB_HOST=<YOUR-DB-HOST> 
export DB_NAME=<YOUR-DB-NAME> 
export DB_USER=<YOUR-DB-USER> 
export DB_PASS=<YOUR-DB-PASS> 
export BASE_URL=<YOUR-BASE-URL>
erb tests/app/config/bootstrap.php.erb > tests/app/config/bootstrap.php
erb codeception.yml.erb > codeception.yml
erb tests/acceptance.suite.yml.erb > tests/acceptance.suite.yml
```

Now you can use the following command to run the test suite:
 
```bash
vendor/bin/codecept run
```

### Translate

If you wish to translate this project you can find the translation templates under ```src/messages/templates```.
When you are done with your translation create a pull-request to the ```develop``` branch.
