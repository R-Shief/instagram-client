<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
      return $app['twig']->render('index.html.twig');
  });

$app->get('/location/{lat}/{lng}', function (Request $request, $lat, $lng) use ($app) {
      $params = $request->query->all();
      $media = $app['instagram']->searchMedia( $lat, $lng, $params);

      return $app['twig']->render('location.html.twig', array('media' => $media));
  })
;

$app->get('/logout', function (Request $request) use ($app) {
      $request->getSession()->invalidate();
      return $app->redirect('/');
  })
;

$app->get('/auth', function () use ($app) {

      $app['instagram.auth']->authorize();
  })
;

$app->get('/token', function (Request $request) use ($app) {
      $code = $app['instagram.auth']->getAccessToken($request->get('code'));
      $app['session']->set('code', $code);
      /** @var \Instagram\CurrentUser $current_user */
      $current_user = $app['instagram']->getCurrentUser();
      $app['session']->set('username', $current_user->getUserName());
      return $app->redirect('/');
  })
;

$app->error(function (\Exception $e, $code) use ($app) {
      if ($app['debug']) {
          return;
      }

      // 404.html, or 40x.html, or 4xx.html, or error.html
      $templates = array(
        'errors/'.$code.'.html',
        'errors/'.substr($code, 0, 2).'x.html',
        'errors/'.substr($code, 0, 1).'xx.html',
        'errors/default.html',
      );

      return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
  });
