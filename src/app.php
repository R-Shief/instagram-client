<?php

use Guzzle\GuzzleServiceProvider;
use Silex\Application;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

$app = new Application();
$app->register(new RoutingServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new \Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
  ));
$app->register(new SessionServiceProvider(), array(
      'session.storage.save_path' => __DIR__.'/../var/sessions'
  ));
$app->extend('twig', function (\Twig_Environment $twig, $app) {
      $twig->addGlobal('app', $app);

      return $twig;
  });

$app['instagram.cache'] = function ($app) {
    return new \Doctrine\Common\Cache\FilesystemCache(__DIR__.'/../var/cache/instagram');
};

$app['instagram.cache.adapter'] = function ($app) {
    return new \Guzzle\Cache\DoctrineCacheAdapter($app['instagram.cache']);
};

$app['instagram.cache.storage'] = function ($app) {
    return new \Guzzle\Plugin\Cache\DefaultCacheStorage(
      $app['instagram.cache.adapter']
    );
};

$app['instagram.cache.can_cache'] = function ($app) {
    return new \Guzzle\Plugin\Cache\CallbackCanCacheStrategy(
      function (\Guzzle\Http\Message\RequestInterface $request) { return true; },
      function (\Guzzle\Http\Message\Response $response) { return true; }
    );
};

$app['instagram.cache.plugin'] = function ($app) {
    return new \Guzzle\Plugin\Cache\CachePlugin(array(
        'storage' => $app['instagram.cache.storage'],
        'can_cache' => $app['instagram.cache.can_cache'],
    ));
};

$app->register(new GuzzleServiceProvider(), array(
    'guzzle.plugins' => array($app['instagram.cache.plugin']),
  ));

$app['instagram.client'] = function ($app) {
    return new \Bangpound\Instagram\Client($app['guzzle.client'], $app['session']);
};

$app['instagram'] = function ($app) {
    return new Instagram\Instagram($app['session']->get('code'), $app['instagram.client']);
};

$app['instagram.auth'] = function ($app) {
    return new Instagram\Auth(array(
      'client_id' => $app['instagram.client_id'],
      'client_secret' => $app['instagram.client_secret'],
      'redirect_uri' => $app['instagram.redirect_uri'],
    ));
};

return $app;
