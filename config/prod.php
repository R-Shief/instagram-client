<?php

// configure your app for the production environment

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');

$app['instagram.client_id'] = '';
$app['instagram.client_secret'] = '';
$app['instagram.redirect_uri'] = '';
