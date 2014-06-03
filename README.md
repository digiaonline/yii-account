yii-account
===========

Extension that provides basic account functionality for the Yii PHP framework.

___NOTE: This project is still under active development and is not stable for production use.___

Requirements
------------

- Secure accounts (password + salt) ___DONE___
- Register (with activation) ___DONE___
- Log in / Log out ___DONE___
- Reset password ___DONE___
- Email sending (with token validation) ___DONE___
- Console command for creating accounts ___DONE___
- Proper README ___WIP___

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
If you are not using Composer, then you need to download the dependencies manually and add the following to your application configuration:

```php
'aliases' => array(
    '\nordsoftware\yii_account' => __DIR__ . '/relative/path/to/yii-account',
    '\YiiPassword' => __DIR__ . '/relative/path/to/yiipassword',
    '\RandomLib' => __DIR__ . 'relative/path/to/randomlib/lib',
    '\SecureLib' => __DIR__ . 'relative/path/to/securelib/lib',
),
```

### Dependencies

- YiiPassword https://github.com/phpnode/yiipassword
- RandomLib https://github.com/ircmaxell/RandomLib
- SecureLib https://github.com/ircmaxell/SecureLib

Run the following command to apply database migrations:

```bash
php yiic.php migrate --migrationPath=account.migrations
```

Usage
-----

Now you should be able to see the login page when you go to the following url:

```
index.php?r=account
```

Contribute
----------

If you wish to contribute to the project feel free to create a pull-request to the ```develop``` branch.