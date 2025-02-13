<?php
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once "vendor/autoload.php";

$config = ORMSetup::createAttributeMetadataConfiguration(
	paths: [__DIR__ . '/src/Entities'],
	isDevMode: true,
);

// configuring the database connection
$connection = DriverManager::getConnection([
	'driver' => 'pdo_mysql',
	'host' => $_ENV['DB_HOST'],
	'port' => $_ENV['DB_PORT'],
	'dbname' => $_ENV['DB_NAME'],
	'user' => $_ENV['DB_USER'],
	'password' => $_ENV['DB_PASS']
], $config);

// obtaining the entity manager
$entityManager = new EntityManager($connection, $config);
return $entityManager;