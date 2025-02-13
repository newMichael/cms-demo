<?php

use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
$_ENV['MAIL_SMTP_AUTH'] = filter_var($_ENV['MAIL_SMTP_AUTH'], FILTER_VALIDATE_BOOLEAN);

$container = new Container();
$dependencies = require_once __DIR__ . '/dependencies.php';
$dependencies($container);

return $container;