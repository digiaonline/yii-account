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

This project was developed with a focus on re-usability, so before you start copy-pasting take a moment of your time
and read through this section to learn how to extend this module properly.

### Custom account model

You can use your own account model as long as you add the following fields to it:

 * __username__ _varchar(255) not null_ logging in
 * __password__ _varchar(255) not null_ logging in
 * __email__ _varchar(255) not null_ sending email
 * __passwordStrategy__ _varchar(255) not null_ password encryption type  
 * __requireNewPassword__ _tinyint(1) not null default '0'_ whether to request a password change
 * __createdAt__ _timestamp null default current_timestamp_ when the account was created
 * __lastActiveAt__ _timestamp null default null_ when the account was last active
 * __status__ _int(11) default '0'_ account status (e.g. inactive, activated)
 
Changing the model used by the extension is easy, simply configure it to use your class instead by adding it to the
class map for the module:

```php
'account' => array(
    'class' => '\nordsoftware\yii_account\Module',
    'classMap' => array(
        'account' => 'MyAccount', // would otherwise default to \nordsoftware\yii_account\models\ar\Account
    ),
),
```

### Custom models, components or forms classes

You can use the class map to configure any classes used by the module, here is a complete list of the available classes:

 * __account__ _\nordsoftware\yii_account\models\ar\Account_ account model
 * __token__ _\nordsoftware\yii_account\models\ar\AccountToken_ account token mode
 * __loginHistory__ _\nordsoftware\yii_account\models\ar\AccountLoginHistory_ login history model
 * __passwordHistory__ _\nordsoftware\yii_account\models\ar\AccountPasswordHistory_ password history model
 * __userIdentity__ _\nordsoftware\yii_account\components\UserIdentity_ user identity
 * __loginForm__ _\nordsoftware\yii_account\models\form\LoginForm_ login form
 * __passwordForm__ _\nordsoftware\yii_account\models\form\PasswordForm_ base form that handles passwords 
 * __signupForm__ _\nordsoftware\yii_account\models\form\SignupForm_ signup form (extends passwordForm)
 * __forgotPassword__ _\nordsoftware\yii_account\models\form\ForgotPasswordForm_ forgot password form
 
### Custom controllers

If you want to use your own controllers you can map them using the module's controller map:

```php
array(
    'account' => array(
        'class' => '\nordsoftware\yii_account\Module',
        'controllerMap' => array(
            'authorize' => 'AuthorizeController', // would otherwise default to \nordsoftware\yii_account\controllers\AuthorizeController
        ),
    ),
),
```

### Custom views

If you want to use your own views with this module you can override the views with your own by placing them either
under your application (```protected\views\account```) or your theme (```themes\views\account```).

### Extending the module itself

You may also want to extend the module itself, e.g. in order to implement proper email sending. In that case you can
extend the module and override the methods necessary and configure your account to use your module instead:

```php
'account' => array(
    'class' => 'MyAccountModule',
),
```

Normally you would need to copy all the views under your module, but we have made it easy so that you can only override
the views you need to and the module will automatically look for the default views under the parent module.

The source code is also quite well documented so the easiest way to find out how to extend properly is to dive into
the code and get to know the logic behind the functionality. Also, if you have any ideas for improvements feel free
to file an issue or create a pull-request.

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
