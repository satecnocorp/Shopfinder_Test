# Shopfinder_Test

This module is the solution for the test case in the selection process for a Magento developer.

The module works as expected in almost any sense with some exceptions.

## Installation

* Download Docker from [https://docs.docker.com/get-docker/](https://docs.docker.com/get-docker/) and install It
* If you work in a windows computer you should consider to install also a linux subsystem like [Ubuntu](https://apps.microsoft.com/store/detail/ubuntu-22041-lts/9PN20MSR04DW)
* install [Warden](https://docs.warden.dev/installing.html)
* install Magento over warden: here the [instructions](https://docs.warden.dev/environments/magento2.html) just in case
* In the "warden shell" command line execute this commands from the root of the project (should be /var/www/html)

```

composer config repositories.rtr_test git https://github.com/satecnocorp/Shopfinder_Test.git
composer require chalhoub/module-shopfinder
composer install
php bin/magento setup:upgrade
php bin/magento cache:flush
rm -rf generated/code/*

```
* create an admin user by executing

```
php bin/magento admin:user:create

```
refer to [this instructions](https://www.mageplaza.com/devdocs/magento-2-add-admin-user-command-line.html#who-will-need-this-guide) to do it

* maybe you should also want to disable two factor authentication so you can enter just with your user and password 

```
php bin/magento module:disable Magento_TwoFactorAuth

```

* now you should be able to navigate to the bakend of the site and log in with your user and password
Go to the admin menu under "Content" and click "shopfinder", there you can add new shops and visualize their information

also you should be able to use the graphql API with a tool like Postman or altair.


## Known issues

* there is no pre-installed data, creating the shops is a manual process so far, It's pending to add a pathc with some sample data
* The shop setters lack of validation for the properties content, so, some fields are sensible to accept data that may cause problems
* the graphQL api does not allow to update images
* the api does not require any authentication and that would potentialy be a security problem
* some of the filters in the admin may not have a forced format to match the object properties
* this read me is not as detailed as it could, and some of the details could not be evident for a non-technical or experienced user.

I hope to have the chance to improve all of theese areas.

