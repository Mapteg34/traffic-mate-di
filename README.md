# Test-task

Questioner: Traffic Mate LTD

# Task description

Please design and implement a simplified dependency injection container in PHP.

It needs to be able to resolve dependencies using:
* Name of a service
* Interface of the service
* Implementation of the service
* Support constructor DI capabilities based on type-hinting
* Manage the life-circle of the objects (singleton/per request/etc)

# Solution description

Container code placed on classes/Container.php file.

For autoload I integrated composer.

For PSR11    I used psr/container package.

I think we are understand, that in real life we don't need to create this, and we can use container from framework: laravel, symfony yii2 can do it without us? :)

# How to use?

You can read code from classes/Container.php, or
```
git clone git@github.com:Mapteg34/traffic-mate-di.git
cd traffic-mate-di
composer install
php -f test.php
```