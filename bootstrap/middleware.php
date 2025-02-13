<?php

use App\Middleware\FlashMiddleware;
use App\Middleware\NotFoundMiddleware;
use Middlewares\TrailingSlash;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\TwigMiddleware;

return function ($app) {
	$container = $app->getContainer();
	$twig = $container->get('view');
	$app->add(TwigMiddleware::create($app, $twig));
	$app->add(new FlashMiddleware($twig));
	$app->add(new NotFoundMiddleware($twig));
	$app->add(new TrailingSlash(false));
	$app->add(new Zeuxisoo\Whoops\Slim\WhoopsMiddleware([
		'enable' => $_ENV['APP_ENV'] === 'dev',
		'editor' => $_ENV['WHOOPS_EDITOR']
	]));
	$app->add(new MethodOverrideMiddleware());
};