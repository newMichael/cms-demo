<?php

use App\Config\Database;
use DI\Container;
use Slim\Views\Twig;

return function(Container $container) {
	$container->set('db', function() {
		return Database::getConnection();
	});
	$container->set('view', function() {
		$twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);
		$twig->addExtension(new App\Twig\AppExtension());
		return $twig;
	});
	$container->set('flash', function() {
    return new \Slim\Flash\Messages();
	});
	$container->set('AuthService', function(Container $container) {
		return new App\Service\AuthService($container->get('db'));
	});
};