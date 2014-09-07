instagram-client
================

This project is a work in progress.

installation
------------

* clone the repository
* `composer install`
* `bower install`
* `cd web; compass compile`
* acquire an instagram key <http://instagram.com/developer/clients/register/>. your redirect URL is `http://localhost:8000`
* put those values in `config/prod.php`
* patch `web/index_dev.php` using the instructions at <http://silex.sensiolabs.org/doc/web_servers.html#php-5-4> for the php 5.4 built in web server.
* run `php -S localhost:8000 -t web/ web/index_dev.php`
