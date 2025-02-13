<?php

use Slim\Factory\AppFactory;

session_start();
$container = require_once __DIR__ . '/../bootstrap/app.php';

AppFactory::setContainer($container);
$app = AppFactory::create();

$middleware = require_once __DIR__ . '/../bootstrap/middleware.php';
$middleware($app);

$routes = require_once __DIR__ . '/../bootstrap/routes.php';
$routes($app);

$app->run();