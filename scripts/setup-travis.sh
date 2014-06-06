#!/bin/bash

mysql -e 'CREATE DATABASE yii_app_test;'
export DB_HOST=127.0.0.1 DB_NAME=yii_app_test DB_USER=root DB_PASS='' BASE_URL='http://localhost:8000/'
erb tests/app/config/bootstrap.php.erb > tests/app/config/bootstrap.php
erb codeception.yml.erb > codeception.yml
erb tests/acceptance.suite.yml.erb > tests/acceptance.suite.yml
composer self-update
composer install --no-interaction --prefer-source
php -S localhost:8000 -t tests/app/www/ > /dev/null 2>&1 &